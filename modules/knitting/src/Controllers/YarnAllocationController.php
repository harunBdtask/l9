<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use SkylarkSoft\GoRMG\Knitting\Models\YarnAllocation;
use SkylarkSoft\GoRMG\Knitting\Models\YarnAllocationBookingDetail;
use SkylarkSoft\GoRMG\Knitting\Models\YarnAllocationDetail;
use SkylarkSoft\GoRMG\Knitting\Models\YarnRequisitionDetail;
use SkylarkSoft\GoRMG\Knitting\Requests\YarnAllocationRequest;
use SkylarkSoft\GoRMG\Knitting\Services\AllocationBookingDataFormatter;
use SkylarkSoft\GoRMG\Knitting\Services\PlaningInfo\PlaningInfoObserverService;
use SkylarkSoft\GoRMG\Knitting\Services\SalesOrderBookingSearch;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\CostingSheetService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use Symfony\Component\HttpFoundation\Response;

class YarnAllocationController extends Controller
{
    public function index()
    {
        $allocations = YarnAllocation::query()->orderBy('id', 'desc')->with('details', 'factory', 'buyer')->get();
        return view('knitting::yarn-allocation.index', compact('allocations'));
    }

    public function create()
    {
        return view('knitting::yarn-allocation.create');
    }

    public function getBookings(Request $request): JsonResponse
    {
        $factoryId = $request->get('factory_id');
        $buyerId = $request->get('buyer_id');
        $searchBy = $request->get('search_by');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $isProduction = $request->get('is_production');
        $styleName = $request->get('style_name');
        $orderNno = $request->get('order_no');

        $data = SalesOrderBookingSearch::for($searchBy)
            ->setFactoryId($factoryId)
            ->setBuyerId($buyerId)
            ->setBookingStartDate($startDate)
            ->setBookingEndDate($endDate)
            ->setIsProduction($isProduction)
            ->setStyleName($styleName)
            ->setOrderNo($orderNno)
            ->response();

        return response()->json($data, Response::HTTP_OK);
    }

    public function store(YarnAllocationRequest $request, YarnAllocation $allocation): JsonResponse
    {
        try {
            $allocation->fill($request->all())->save();
            return response()->json($allocation, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function storeDetails(Request $request): JsonResponse
    {
        try {
            if ($request->has('yarn_allocation_id')) {
                foreach ($request->get('editData') as $key => $value) {
                    $query = YarnAllocationDetail::query();
                    if (empty($value['id'])) {
                        $query->insert($value);
                    } else {
                        $update['allocated_qty'] = $value['allocated_qty'];
                        $update['yarn_lot'] = $value['yarn_lot'];
                        if (isset($value['supplier_id'])) {
                            $update['supplier_id'] = $value['supplier_id'];
                        }
                        $query->where('id', $value['id'])
                            ->update($update);
                    }
                }
                $status = Response::HTTP_OK;
            } else {
                YarnAllocationDetail::query()->insert($request->all());
                $status = Response::HTTP_CREATED;
            }
            return response()->json('Success', $status);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function storeBreakdowns(): JsonResponse
    {
        try {
            $allocationId = request()->get('allocation_id');
            foreach (request()->get('breakdowns') as $key => $item) {
                $item['yarn_allocation_id'] = $allocationId;
                $formatData = (new AllocationBookingDataFormatter($item))->format();
                (new YarnAllocationBookingDetail($formatData))->save();
            }

            $yarnAllocationDetails = YarnAllocationBookingDetail::query()
                ->with('yarnAllocation', 'bodyPart', 'colorType', 'gmtColor', 'itemColor')
                ->where('yarn_allocation_id', $allocationId);

            $breakDownGroup = (clone $yarnAllocationDetails)->select('*', DB::raw('SUM(gray_qty) as total_qty , SUM(booking_qty) as total_booking_qty'))
                ->groupBy([
                    'fabric_description',
                    'fabric_dia',
                    'fabric_gsm',
                    'body_part_id',
                    'color_type_id',
                    'dia_type_id',
                    'cons_uom'
                ])->get();

            $buyersId = collect($breakDownGroup)->pluck('yarnAllocation.buyer_id')->toArray();
            $buyers = Buyer::query()->whereIn('id', $buyersId)->get()->keyBy('id');
            $allocationDetails = (clone $yarnAllocationDetails)->get();
            $breakDownGroup->map(function ($breakDownGroup) use ($allocationId, $buyers, $allocationDetails) {
                $detailIds = $allocationDetails->where('fabric_description', $breakDownGroup->fabric_description)->pluck('id')->toArray();
                $gmtColors = $allocationDetails->pluck('gmt_color')->unique()->values()->implode(', ');
                $itemColors = $allocationDetails->pluck('item_color')->unique()->values()->implode(', ');
                $buyer = $buyers[$breakDownGroup->yarnAllocation->buyer_id];
                $bodyPart = $breakDownGroup->bodyPart->name ?? '';

                $details = "$breakDownGroup->fabric_description, $breakDownGroup->fabric_dia, $breakDownGroup->fabric_gsm, " .
                    "$bodyPart, $breakDownGroup->color_range, $breakDownGroup->dia_type, $breakDownGroup->cons_uom";

                PlaningInfoObserverService::for(YarnAllocation::class)
                    ->setProgrammableId($allocationId)
                    ->setDetailsIds($detailIds)
                    ->setDetails($details)
                    ->setTotalQty($breakDownGroup->total_qty)
                    ->setBookingNo($breakDownGroup->yarnAllocation->booking_no)
                    ->setBuyerName($buyer->name)
                    ->setStyleName($breakDownGroup->yarnAllocation->style_name)
                    ->setUniqueId($breakDownGroup->yarnAllocation->uniq_id)
                    ->setPoNos($breakDownGroup->yarnAllocation->order_number ?? '')
                    ->setBodyPart($bodyPart)
                    ->setColorType($breakDownGroup->colorType->color_types ?? '')
                    ->setGmtColor($gmtColors)
                    ->setItemColor($itemColors)
                    ->setFabricDescription($breakDownGroup->fabric_description)
                    ->setFabricGsm($breakDownGroup->fabric_gsm)
                    ->setFabricDia($breakDownGroup->fabric_dia)
                    ->setDiaType($breakDownGroup->dia_type)
                    ->setBookingQty($breakDownGroup->total_booking_qty)
                    ->setFabricNatureId($breakDownGroup->fabric_nature_id)
                    ->setFabricNature($breakDownGroup->fabric_nature)
                    ->store();
            });

            return response()->json('Created', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function bookingDetails(): JsonResponse
    {
        $budget = Budget::query()->where('job_no', request()->get("uniqueId"))
            ->with('fabricCosting')
            ->first();

        $fabricForm = $budget->fabricCosting->details['details']['fabricForm'] ?? [];
        $totalQty = [];
        if (!empty($fabricForm)) {
            $totalQty = CostingSheetService::formatYarnProductionData($budget, $fabricForm);
        }
        $yarnCostForm = $budget->fabricCosting->details['details']['yarnCostForm'] ?? [];

        $data = collect($yarnCostForm)->transform(function ($yarnCost, $key) use ($totalQty) {
            $yarnCost['count_query_value'] = YarnCount::query()->where('id', $yarnCost['count'])->first()->yarn_count ?? '';
            $yarnCost['total_yarn_qty'] = number_format($totalQty[$key]['total_qty'], 2, '.', '');
            $yarnCost['allocated_qty'] = null;
            $yarnCost['lot'] = null;
            $yarnCost['supplier_id'] = null;
            return $yarnCost;
        });
        return response()->json([
            'data' => $data,
            'common' => collect($data)->first()['fabric_description'] ?? ''
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        $data = $this->getYarnAllocations($id);
        return view('knitting::yarn-allocation.view', compact('data'));
    }

    public function pdf($id)
    {
        $data = $this->getYarnAllocations($id);
        $pdf = PDF::setOption('enable-local-file-access', true)->loadView('knitting::yarn-allocation.pdf',
            compact('data')
        )->setPaper('a4')->setOrientation('portrait')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);
        return $pdf->stream('yarn_allocation_report');
    }

    public function edit($id): JsonResponse
    {
        $data = $this->getYarnAllocations($id);
        return response()->json($data, 200);
    }

    private function getYarnAllocations($id)
    {
        return YarnAllocation::query()
            ->where('id', $id)
            ->with('details.supplier', 'factory', 'buyer', 'bookingDetails')
            ->firstOrFail();
    }

    public function delete(YarnAllocation $allocation): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $allocation->with(['planInfo' => function ($query) {
                return $query->withCount('knittingPrograms');
            }])
                ->first();
            $knitPrograms = collect($data['planInfo'])->pluck('knitting_programs_count')->some(function ($value) {
                return $value === 1;
            });
            if ($knitPrograms) {
                session()->flash('danger', 'Program exists for this allocation');
                return back();
            }
            $allocation->details()->delete();
            $allocation->bookingDetails()->delete();
            $allocation->planInfo()->delete();
            $allocation->delete();
            DB::commit();
            session()->flash('success', 'Successfully Deleted');
        } catch (\Throwable $e) {
            session()->flash('danger', $e->getMessage());
        }

        return redirect()->back();
    }

    public function searchFilterData(): JsonResponse
    {
        $queryMain = FabricBookingDetailsBreakdown::query()
            ->whereHas('fabricBooking', function ($query) {
                $query->where('fabric_source', 1);
            });
        $mainFabricStyles = $queryMain->get()->pluck('style_name')->unique()->values();
        $mainFabricOrderNo = $queryMain->get()->pluck('po_no')->unique()->values();

        $queryShort = ShortFabricBookingDetails::query()
            ->whereHas('shortFabricBooking', function ($query) {
                $query->where('fabric_source', 1);
            });
        $shortFabricStyles = $queryShort->get()->pluck('style_name')->unique()->values();
        $shortFabricOrderNo = $queryShort->get()->pluck('po_no')->unique()->values();

        return response()->json([
            'mainFabricStyles' => $mainFabricStyles,
            'mainFabricOrderNo' => $mainFabricOrderNo,
            'shortFabricStyles' => $shortFabricStyles,
            'shorFabricOrderNo' => $shortFabricOrderNo
        ]);
    }

    public function getYarnStockByLot($lot): JsonResponse
    {
        $stockInfo = YarnStockSummary::query()
            ->where('yarn_lot', $lot)
            ->with('company', 'composition', 'yarnCount', 'type')
            ->first();

        $allocated_qty = YarnAllocationDetail::query()->where('yarn_lot', $lot)->get()->sum('allocated_qty') ?? 0;
        $unallocated_qty = $stockInfo->balance - $allocated_qty ?? 0;
        if (request()->get('isRequisition')) {
            $requisition_qty = YarnRequisitionDetail::query()->where('yarn_lot', $lot)->get()->sum('requisition_qty') ?? 0;
            $unallocated_qty = $allocated_qty - $requisition_qty;
        }

        return response()->json([
            'company' => $stockInfo->company->factory_name ?? '',
            'current_stock' => $stockInfo->balance,
            'unallocated_qty' => $unallocated_qty,
            'yarn_type' => $stockInfo->type->name ?? '',
            'yarn_composition' => $stockInfo->composition->yarn_composition ?? '',
            'yarn_count' => $stockInfo->yarnCount->yarn_count ?? '',
            'age_days' => Carbon::parse($stockInfo->created_at)->diffInDays(),
        ]);
    }

    public function getYarnAllocationDetail($programId, $colorId): JsonResponse
    {
        $yarnAllocationDetail = YarnAllocationDetail::query()
            ->with(['yarn_composition', 'yarn_count', 'yarn_type'])
            ->where('knitting_program_id', $programId)
            ->where('knitting_program_color_id', $colorId)
            ->select('*', 'id as knit_yarn_allocation_detail_id')
            ->get()->map(function ($yarnAllocationDetail) {
                return [
                    'knitting_program_id' => $yarnAllocationDetail->knitting_program_id,
                    'knit_yarn_allocation_detail_id' => $yarnAllocationDetail->knitting_program_color_id,
                    'yarn_type_value' => $yarnAllocationDetail->yarn_type->name,
                    'yarn_count_value' => $yarnAllocationDetail->yarn_count->yarn_count,
                    'yarn_composition_value' => $yarnAllocationDetail->yarn_composition->yarn_composition,
                    'yarn_composition_id' => $yarnAllocationDetail->yarn_composition_id,
                    'yarn_count_id' => $yarnAllocationDetail->yarn_count_id,
                    'yarn_type_id' => $yarnAllocationDetail->yarn_type_id,
                    'yarn_color' => $yarnAllocationDetail->yarn_color,
                    'yarn_brand' => $yarnAllocationDetail->yarn_brand,
                    'yarn_lot' => $yarnAllocationDetail->yarn_lot,
                    'store_id' => $yarnAllocationDetail->store_id,
                    'uom_id' => $yarnAllocationDetail->uom_id,
                    'vdq' => null,
                ];
            });

        return response()->json($yarnAllocationDetail, Response::HTTP_OK);
    }
}
