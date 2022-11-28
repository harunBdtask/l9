<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\HourWiseFinishingProduction;
use Symfony\Component\HttpFoundation\Response;

class HourlyFinishingProductionController extends Controller
{
    public function index()
    {
        return view('finishingdroplets::pages.hourly_finishing_productions');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $hourDetails = $request->get('hourDetails');
            $ironProduction
                = $polyProduction
                = $packingProduction
                = $ironRejectionProduction
                = $polyRejectionProduction
                = $packingRejectionProduction
                = $reasonProduction
                = $this->requestArray($request);

            if (
                HourWiseFinishingProduction::query()
                ->where($ironProduction)->count()
                && !$request->get('isEditing')
            ) {
                return response()->json("Already Exists", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $ironProduction['production_type'] = HourWiseFinishingProduction::IRON;
            $ironRejectionProduction['production_type'] = HourWiseFinishingProduction::IRON_REJECTION;
            $polyProduction['production_type'] = HourWiseFinishingProduction::POLY;
            $polyRejectionProduction['production_type'] = HourWiseFinishingProduction::POLY_REJECTION;
            $packingProduction['production_type'] = HourWiseFinishingProduction::PACKING;
            $packingRejectionProduction['production_type'] = HourWiseFinishingProduction::PACKING_REJECTION;
            $reasonProduction['production_type'] = HourWiseFinishingProduction::REASON;

            $ironProduction = HourWiseFinishingProduction::query()->firstOrNew($ironProduction);
            $ironRejectionProduction = HourWiseFinishingProduction::query()->firstOrNew($ironRejectionProduction);
            $polyProduction = HourWiseFinishingProduction::query()->firstOrNew($polyProduction);
            $polyRejectionProduction = HourWiseFinishingProduction::query()->firstOrNew($polyRejectionProduction);
            $packingProduction = HourWiseFinishingProduction::query()->firstOrNew($packingProduction);
            $packingRejectionProduction = HourWiseFinishingProduction::query()->firstOrNew($packingRejectionProduction);
            $reasonProduction = HourWiseFinishingProduction::query()->firstOrNew($reasonProduction);

            for ($i = 0; $i < 24; $i++) {
                $ironProduction['hour_' . $i] = (int)str_replace(' ', '', $hourDetails['iron'][$i] ?? 0);
                $ironRejectionProduction['hour_' . $i] = (int)str_replace(' ', '', $hourDetails['iron_rejection'][$i] ?? 0);
                $polyProduction['hour_' . $i] = (int)str_replace(' ', '', $hourDetails['poly'][$i] ?? 0);
                $polyRejectionProduction['hour_' . $i] = (int)str_replace(' ', '', $hourDetails['poly_rejection'][$i] ?? 0);
                $packingProduction['hour_' . $i] = (int)str_replace(' ', '', $hourDetails['packing'][$i] ?? 0);
                $packingRejectionProduction['hour_' . $i] = (int)str_replace(' ', '', $hourDetails['packing_rejection'][$i] ?? 0);
                $reasonProduction['hour_' . $i] = $hourDetails['reason'][$i] ?? null;
            }

            $exception = DB::transaction(function () use ($ironProduction, $ironRejectionProduction, $polyProduction, $polyRejectionProduction, $packingProduction, $packingRejectionProduction, $reasonProduction) {
                $ironProduction->save();
                $ironRejectionProduction->save();
                $polyProduction->save();
                $polyRejectionProduction->save();
                $packingProduction->save();
                $packingRejectionProduction->save();
                $reasonProduction->save();
            });
            
            if (!is_null($exception)) {
                return response()->json(\SOMETHING_WENT_WRONG, Response::HTTP_BAD_REQUEST);
            }
            return response()->json("Successfully Stored", Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function getList(Request $request): Collection
    {
        $finishProduction = HourWiseFinishingProduction::query()
            ->where('finishing_floor_id', $request->get('finishing_floor_id'))
            ->when($request->get('finishing_table_id'), function ($q, $request) {
                return $q->where('finishing_table_id', $request->get('finishing_table_id'));
            })
            ->where('production_date', date('Y-m-d'))
            ->get();

        $finishProductionGrouped = HourWiseFinishingProduction::query()
            ->with([
                'buyer',
                'order',
                'item',
                'purchaseOrder',
                'color'
            ])
            ->where('finishing_floor_id', $request->get('finishing_floor_id'))
            ->where('production_date', date('Y-m-d'))
            ->when($request->get('finishing_table_id'), function ($q, $request) {
                return $q->where('finishing_table_id', $request->get('finishing_table_id'));
            })
            ->groupBy('production_date', 'finishing_floor_id', 'finishing_table_id', 'buyer_id', 'order_id', 'item_id', 'po_id', 'color_id')
            ->get();

        if (!$request->get('finishing_table_id')) {
            $finishProduction = $finishProduction->whereNull('finishing_table_id');
            $finishProductionGrouped = $finishProductionGrouped->whereNull('finishing_table_id');
        }

        return $this->getMap($finishProductionGrouped, $finishProduction->toArray());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function view(Request $request): JsonResponse
    {
        $editData = $this->requestArray($request);
        $hourWiseProduction = HourWiseFinishingProduction::query()->where($editData)->get();
        $editData['hourDetails'] = [
            'iron' => [],
            'iron_rejection' => [],
            'poly' => [],
            'poly_rejection' => [],
            'packing' => [],
            'packing_rejection' => [],
            'reason' => []
        ];
        foreach ($hourWiseProduction as $production) {
            if (isset($production['production_type'])) {
                for ($i = 0; $i < 24; $i++) {
                    $editData['hourDetails'][$production['production_type']][$i] = $production['hour_' . $i];
                }
            }
        }
        return response()->json($editData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $productions = $this->requestArray($request);

            $ids = HourWiseFinishingProduction::query()
                ->where($productions)
                ->pluck('id')
                ->toArray();
            $exception = DB::transaction(function () use ($ids) {
                for ($j = 0; $j < count($ids); $j++) {
                    HourWiseFinishingProduction::find($ids[$j])->delete();
                }
            });
            if (!is_null($exception)) {
                return response()->json(\SOMETHING_WENT_WRONG, Response::HTTP_BAD_REQUEST);
            }

            return response()->json("Successfully Deleted", Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function requestArray(Request $request): array
    {
        return [
            'production_date' => $request->get('production_date'),
            'finishing_floor_id' => $request->get('finishing_floor_id'),
            'finishing_table_id' => $request->get('finishing_table_id'),
            'buyer_id' => $request->get('buyer_id'),
            'order_id' => $request->get('order_id'),
            'item_id' => $request->get('item_id'),
            'po_id' => $request->get('po_id'),
            'color_id' => $request->get('color_id'),
        ];
    }

    /**
     * @param $finishProductionGrouped
     * @param array $finishProduction
     * @return Collection
     */
    private function getMap($finishProductionGrouped, array $finishProduction): Collection
    {
        return collect($finishProductionGrouped)->map(function ($collection) use ($finishProduction) {

            $keys = [
                'buyer_id' => $collection->buyer_id,
                'production_date' => $collection->production_date,
                'finishing_floor_id' => $collection->finishing_floor_id,
                'finishing_table_id' => $collection->finishing_table_id,
                'order_id' => $collection->order_id,
                'po_id' => $collection->po_id,
                'color_id' => $collection->color_id,
                'item_id' => $collection->item_id,
            ];

            $production = collect($finishProduction)
                ->where('buyer_id', $collection->buyer_id)
                ->where('production_date', $collection->production_date)
                ->where('finishing_floor_id', $collection->finishing_floor_id)
                ->where('finishing_table_id', $collection->finishing_table_id)
                ->where('order_id', $collection->order_id)
                ->where('po_id', $collection->po_id)
                ->where('item_id', $collection->item_id)
                ->where('color_id', $collection->color_id);


            $ironProductionType = $production->where('production_type', 'iron')
                ->first();

            $ironRejectionProductionType = $production->where('production_type', 'iron_rejection')
                ->first() ?? [];

            $polyProductionType = $production->where('production_type', 'poly')
                ->first();

            $polyRejectionProductionType = $production->where('production_type', 'poly_rejection')
                ->first() ?? [];

            $packingProductionType = $production->where('production_type', 'packing')
                ->first();

            $packingRejectionProductionType = $production->where('production_type', 'packing_rejection')
                ->first() ?? [];

            $totalIron = 0;
            $totalIronRejection = 0;
            $totalPoly = 0;
            $totalPolyRejection = 0;
            $totalPacking = 0;
            $totalPackingRejection = 0;
            for ($i = 0; $i < 24; $i++) {
                $hourKey = "hour_$i";
                $totalIron += $ironProductionType[$hourKey];
                $totalPoly += $polyProductionType[$hourKey];
                $totalPacking += $packingProductionType[$hourKey];
                if (isset($ironRejectionProductionType[$hourKey])) {
                    $totalIronRejection += $ironRejectionProductionType[$hourKey];
                }
                if (isset($polyRejectionProductionType[$hourKey])) {
                    $totalPolyRejection += $polyRejectionProductionType[$hourKey];
                }
                if (isset($packingRejectionProductionType[$hourKey])) {
                    $totalPackingRejection += $packingRejectionProductionType[$hourKey];
                }
            }

            return $keys + [
                'buyer' => $collection->buyer->name,
                'style' => $collection->order->style_name,
                'item' => $collection->item->name,
                'purchase_order' => $collection->purchaseOrder->po_no,
                'color' => $collection->color->name,
                'total_iron' => $totalIron,
                'total_iron_rejection' => $totalIronRejection,
                'total_poly' => $totalPoly,
                'total_poly_rejection' => $totalPolyRejection,
                'total_packing' => $totalPacking,
                'total_packing_rejection' => $totalPackingRejection
            ];
        });
    }
}
