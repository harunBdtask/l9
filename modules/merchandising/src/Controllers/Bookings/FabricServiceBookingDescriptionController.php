<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricServiceBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricServiceBookingDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\Process;
use Symfony\Component\HttpFoundation\Response;

class FabricServiceBookingDescriptionController extends Controller
{
    public function descriptionsList()
    {
        return \response()->json(['']);
    }

    public function descriptions(Request $request)
    {
        try {
            $bookingServiceId = $request->booking_service_id;
            $details = $request->details;
            $budgetId = $details['budget_id'];
            $bookingService = FabricServiceBooking::findOrFail($bookingServiceId);
            $processId = $bookingService->process;
            $process = Process::findOrFail($processId);
            $costingData = BudgetCostingDetails::where('budget_id', $budgetId)->whereType('fabric_costing')->first();
            $fabricDescriptions = collect($costingData->details['details']['conversionCostForm'])
                ->where('process', $process->process_name)
                ->pluck('fabric_description')
                ->map(function ($d) use ($budgetId) {
                    $arr = explode(',', $d);
                    $bodyPart = $arr[0];
                    array_shift($arr);
                    $description = trim(implode(',', $arr));

                    return [
                        'body_part' => $bodyPart,
                        'id' => $d,
                        'text' => $d,
                        'description' => $description,
                        'budget_id' => $budgetId,
                    ];
                })
                ->all();

            return response()->json($fabricDescriptions);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function poForColors()
    {
        request()->validate(['description' => 'required', 'budget_id' => 'required']);
        $description = request('description');
        $budget_id = request('budget_id');
        $costing = $this->getCostingByBudgetId($budget_id);

        $data = $costing->details['details']['fabricForm'];
        $explodeDescription = explode(', ', $description);

        $descriptionWise = collect($data)
            ->where('fabric_composition_value', $explodeDescription[1])
            ->pluck('greyConsForm.details');


        $poNo = $descriptionWise->flatten(1)
            ->where('finish_cons', '>', 0)
            ->when(request('colors'), function ($collection) {
                return $collection->whereIn('color_id', request('colors'));
            })
            ->pluck('po_no')
            ->unique()
            ->values();

        return response()->json($poNo);
    }

    public function colorsForDescription()
    {
        $budget_id = request('budget_id');
        $description = request('description');
        $costing = $this->getCostingByBudgetId($budget_id);

        $colors = collect($costing->details['details']['conversionCostForm'] ?? [])
            ->where('fabric_description', $description)
            ->filter(function ($d) {
                return !is_null($d['chargeUnitForm']);
            })
            ->pluck('chargeUnitForm.details')
            ->flatten(1)
            ->map(function ($f) {
                return [
                    'id' => $f['fabric_color_id'],
                    'text' => $f['fabric_color'],
                ];
            })
            ->unique('id')
            ->all();

        return response()->json($colors);
    }

    public function budgetDetailsData()
    {
        $budgetId = request('budget_id');
        $colorsId = request('colors');
        $poNos = request('poNumbers');

        $process = Process::query()->where('id', request('process'))->first();

        $budget = Budget::findOrFail($budgetId);

        $styleName = $budget->style_name;

        $arr = explode(',', request('fabric_description'));
        array_shift($arr);
        $description = trim(implode(',', $arr));

        $costing = $this->getCostingByBudgetId($budgetId);

        // Get Fabric Description From conversionCostForm
        $conversionCostForm = $costing->details['details']['conversionCostForm'] ?? [];
        $targetFabricForm = collect($conversionCostForm)->filter(function ($form) use ($description) {
            return strpos($form['fabric_description'], $description);
        })->where('process', $process->process_name)->pluck('chargeUnitForm.details')->flatten(1);

        $greyCondData = collect($costing->details['details']['fabricForm'])
            ->where('fabric_composition_value', $description)
            ->pluck('greyConsForm.details')
            ->flatten(1)
            ->when($poNos, function ($collection) use ($poNos) {
                return $collection->whereIn('po_no', $poNos);
            })
            ->when($colorsId, function ($collection) use ($colorsId) {
                return $collection->whereIn('color_id', $colorsId);
            });

        $details = $greyCondData->map(function ($data) use ($greyCondData, $styleName, $description, $budgetId, $conversionCostForm, $targetFabricForm) {
            $colorWiseQty = $greyCondData->where('color_id', $data['color_id'])->sum('total_qty');
            $prevWOQty = FabricServiceBookingDetail::query()
                ->where('budget_id', $budgetId)
                ->where('item_color_id', $data['color_id'])
                ->where('po_no', $data['po_no'])
                ->sum('wo_qty');

            $WOQty = $colorWiseQty - $prevWOQty;

            $chargeUnit = collect($targetFabricForm)->where('color_id', $data['color_id'])->first();
            $defaultAmount = collect($conversionCostForm)
                    ->where('fabric_description', request('fabric_description'))
                    ->first()['unit'] ?? 0;
            $rate = $chargeUnit ? (float)$chargeUnit['charge_unit'] : (float)$defaultAmount;

            return array_merge($data, [
                'budget_id' => $budgetId,
                'style_name' => $styleName,
                'fabric_description' => $description,
                'gmts_color_id' => $data['color_id'],
                'item_color_id' => $data['color_id'],
                'labdip_no' => '',
                'lot' => '',
                'yarn_count_id' => null,
                'yarn_composition_id' => null,
                'brand_id' => null,
                'mc_dia' => null,
                'finish_dia' => null,
                'finish_gsm' => null,
                'stich_length' => null,
                'mc_gauge' => null,
                'uom_id' => ApplicationConstant::DEFAULT_UOM,
                'budget_qty' => $colorWiseQty,
                'balance_qty' => format($WOQty),
                'wo_qty' => format($WOQty),
                'delivery_date' => null,
                'rate' => $rate,
                'total_amount' => format($WOQty * $rate),
            ]);
        })
            ->where('balance_qty', '>', 0)
            ->unique('gmts_color_id')
            ->values();

        return response()->json($details);
    }

    private function getCostingByBudgetId($budget_id)
    {
        return BudgetCostingDetails::where('budget_id', $budget_id)
            ->whereType('fabric_costing')
            ->first();
    }

    /**
     * @throws \Throwable
     */
    public function store(FabricServiceBooking $serviceBooking, Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            collect($request->all())->each(function ($detail) use ($serviceBooking) {
                $detail['amount'] = $detail['wo_qty'] * $detail['rate'];
                $detail['balance_qty'] = $detail['budget_qty'] - $detail['wo_qty'];
                if (isset($detail['id'])) {
                    $serviceBooking->details()->find($detail['id'])->update((array)$detail);
                } else {
                    $serviceBooking->details()->create((array)$detail);
                }
            });
            DB::commit();

            return response()->json("Details Saved", Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(FabricServiceBooking $serviceBooking): JsonResponse
    {
        try {
            $serviceBooking->load([
                'details.yarnCount',
                'details.yarnComposition',
                'details.brand',
                'details.uom',
                'details.garmentsColor',
                'details.itemColor',
            ]);

            return response()->json($serviceBooking, Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        try {
            FabricServiceBookingDetail::query()->findOrFail($id)->delete();

            return response()->json('Fabric Service Booking Details Deleted Successfully', Response::HTTP_ACCEPTED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
