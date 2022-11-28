<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\FabricSalesOrder;

use App\Exceptions\DeleteNotPossibleException;
use PDF;
use Exception;
use NumberFormatter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\SystemSettings\Models\Process;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use SkylarkSoft\GoRMG\Knitting\Models\PlanningInfo;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorRange;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\Knitting\Requests\FabricSalesOrderRequest;
use SkylarkSoft\GoRMG\Knitting\Services\BookingData\BookingDataAdapter;
use SkylarkSoft\GoRMG\Knitting\Services\Formatter\RequisitionFormatAdapter;
use SkylarkSoft\GoRMG\Knitting\Services\SalesOrderBookingSearch;
use SkylarkSoft\GoRMG\Knitting\Services\PlaningInfo\PlaningInfoObserverService;
use SkylarkSoft\GoRMG\Knitting\Exports\FabricSalesOrderViewV2Export;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class FabricSalesOrderController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year');
        $buyerId = $request->input('buyer_id');
        $location = $request->input('location');
        $styleName = $request->input('style_name');
        $bookingNo = $request->input('booking_no');
        $type = $request->input('type');
        $withinGroup = $request->input('within_group');
        $bookingDate = $request->input('booking_date');
        $salesOrderNo = $request->input('sales_order_no');
        $bookingTypeStatus = $request->input('booking_type_status');
        $orderStatus = $request->input('order_status');

        $data['buyer'] = Buyer::all();
        $data['salesOrderNo'] = FabricSalesOrder::query()->pluck('sales_order_no')->unique()->values();
        $data['fabricSalesOrderBookingNo'] = FabricSalesOrder::query()->pluck('booking_no')->unique()->values();
        $data['salesOrders'] = FabricSalesOrder::query()
            ->with(['buyer', 'currency'])
            ->when($year, Filter::yearFilter('created_at', $year))
            ->when($buyerId, Filter::applyFilter('buyer_id', $buyerId))
            ->when($bookingNo, function ($query) use ($bookingNo) {
                $query->where('booking_no', 'LIKE', '%' . $bookingNo . '%');
            })
            ->when($type, function ($query) use ($type) {
                $query->where('booking_type', $type);
            })
            ->when($withinGroup, Filter::applyFilter('within_group', $withinGroup))
            ->when($salesOrderNo, Filter::applyFilter('sales_order_no', $salesOrderNo))
            ->when($bookingDate, Filter::applyFilter('booking_date', $bookingDate))
            ->when($styleName, Filter::applyFilter('style_name', $styleName))
            ->when($location, Filter::applyFilter('location', $location))
            ->when($bookingTypeStatus, Filter::applyFilter('booking_type_status', $bookingTypeStatus))
            ->when($orderStatus, Filter::applyFilter('order_status', $orderStatus))
            ->orderByDesc('id')
            ->paginate();

        //TODO REFACTOR;

        $data['dashboardOverview'] = [
            'Not Started' => 0,
            'In Progress' => 0,
            'On Hold' => 0,
            'Cancelled' => 0,
            'Finished' => 0
        ];
//        dd($data);
        return view('knitting::fabricSalesOrder.index', $data);
    }

    public function create($id = null)
    {
        return view('knitting::fabricSalesOrder.create');
    }

    /**
     * @throws Throwable
     */
    public function store(FabricSalesOrderRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $salesOrder = FabricSalesOrder::query()->firstOrNew(['id' => $request->input('id')]);
            $salesOrder->fill($request->all())->save();

            $salesOrderDetailIds = array();
            foreach ($request->get('details') as $input) {
                $salesOrderDetail = $salesOrder->breakdown()->firstOrNew(['id' => $input['id'] ?? null]);
                $salesOrderDetail->fill($input)->save();
                $salesOrderDetailIds[] = $salesOrderDetail->id;
            }
            $salesOrder->breakdown()->whereNotIn('id', $salesOrderDetailIds)->delete();

            $bookingData = BookingDataAdapter::for($salesOrder)->format();
            $salesOrderGroupBy = $this->salesOrderBreakdownGroupBy($salesOrder);
            foreach ($salesOrderGroupBy as $breakDownGroup) {
                $saleOrderDetailIQuery = FabricSalesOrderDetail::query()
                    ->with(['bodyPart'])
                    ->where('fabric_sales_order_id', $salesOrder->id)
                    ->where('fabric_gsm', $breakDownGroup->fabric_gsm)
                    ->where('body_part_id', $breakDownGroup->body_part_id)
                    ->where('fabric_description', $breakDownGroup->fabric_description)
                    ->get();

                $saleOrderDetailIdes = $saleOrderDetailIQuery->pluck('id')->toArray();
                $gmtColors = $saleOrderDetailIQuery->pluck('gmt_color')
                    ->unique()->values()->implode(', ');
                $itemColors = $saleOrderDetailIQuery->pluck('item_color')
                    ->unique()->values()->implode(', ');

                $saleOrderDetailCollection = collect([
                    $breakDownGroup->body_part_id,
                    $breakDownGroup->fabric_description,
                    $breakDownGroup->fabric_gsm,
                    $breakDownGroup->cons_uom,
                    $breakDownGroup->fabric_dia,
                    $breakDownGroup->dia_type_id,
                    $breakDownGroup->color_type_id,
                ])->values()->implode(', ');

                $buyer = Buyer::query()->find($salesOrder->buyer_id);
                PlaningInfoObserverService::for(FabricSalesOrder::class)
                    ->setProgrammableId($breakDownGroup->fabric_sales_order_id)
                    ->setDetailsIds($saleOrderDetailIdes)
                    ->setBuyerName(optional($buyer)->name)
                    ->setBuyerId(optional($buyer)->id)
                    ->setDetails($saleOrderDetailCollection)
                    ->setTotalQty($breakDownGroup->total_qty)
                    ->setStyleName($salesOrder->style_name ?? '')
                    ->setBookingNo($salesOrder->booking_no ?? '')
                    ->setBookingType($salesOrder->booking_type ?? '')
                    ->setBookingDate($salesOrder->booking_date ?? '')
                    ->setUniqueId($bookingData->budget_job_no ?? '')
                    ->setPoNo(collect($bookingData->detailsBreakdown ?? [])->pluck('po_no')->unique()->implode(', '))
                    ->setBodyPart($breakDownGroup->body_part_id)
                    ->setColorType($breakDownGroup->color_type_id)
                    ->setGmtColor($gmtColors)
                    ->setItemColor($itemColors)
                    ->setDiaType($breakDownGroup->dia_type_id)
                    ->setFabricDia($breakDownGroup->fabric_dia)
                    ->setFabricGsm($breakDownGroup->fabric_gsm)
                    ->setFabricNature($breakDownGroup->fabric_nature)
                    ->setBookingQty($breakDownGroup->total_booking_qty)
                    ->setFabricNatureId($breakDownGroup->fabric_nature_id)
                    ->setFabricDescription($breakDownGroup->fabric_description)
                    ->store();
            }

            DB::commit();
            return response()->json($this->output($salesOrder), Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function view($id)
    {
        try {
            $fabricSalesOrder = FabricSalesOrder::query()
                ->with(['currency', 'factory', 'buyerData:id,name', 'breakdown.bodyPart', 'breakdown.fabricColor', 'breakdown.colorType'])
                ->findOrFail($id);
            $data['salesOrder'] = $fabricSalesOrder;
            $data['digit'] = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            if (request('pdf')) {
                return PDF::loadView('knitting::fabricSalesOrder.pdf', $data)
                    ->setOrientation('landscape')
                    ->stream('fabric_sales_order_report.pdf');
            }
            // return $data;
            return view('knitting::fabricSalesOrder.view', $data);
        } catch (Exception $exception) {
            return back()->with('danger', 'Unable To Preview. Please Contact To Support');
        }
    }

    /**
     * @throws Throwable
     */
    public function edit($id): JsonResponse
    {
        try {
            $salesOrder = FabricSalesOrder::query()->with(['currency', 'breakdown', 'teamLeader'])->findOrFail($id);
            $salesOrder['grouped_breakdown'] = $this->salesOrderBreakdownGroupBy($salesOrder);

            return response()->json($this->output($salesOrder), Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function salesOrderBreakdownGroupBy($salesOrder)
    {
        return $salesOrder->breakdown()
            ->select('*', DB::raw('SUM(finish_qty) as total_qty, SUM(booking_qty) as total_booking_qty'))
            ->groupBy('fabric_gsm', 'body_part_id', 'fabric_description')
            ->get();
    }

    protected function output($salesOrder): array
    {
        $buyer = Buyer::query()->find($salesOrder->buyer_id);
        $season = Season::query()->find($salesOrder->season_id);
        return [
            "id" => $salesOrder->id ?? null,
            "job_no" => null,
            "remarks" => $salesOrder->remarks,
            "unit_id" => $salesOrder->unit_id,
            "unit_value" => $salesOrder->unitData['text'],
            "location" => $salesOrder->location,
            "buyer_id" => $salesOrder->buyer_id,
            "season_id" => $salesOrder->season_id,
            "attention" => $salesOrder->attention,
            "ship_mode" => $salesOrder->ship_mode,
            "booking_id" => null,
            "factory_id" => $salesOrder->factory_id,
            "style_name" => $salesOrder->style_name,
            "season_name" => optional($season)->season_name,
            "within_group" => $salesOrder->within_group,
            "is_approve" => null,
            "created_by" => null,
            "buyer_value" => optional($buyer)->name,
            "booking_no" => $salesOrder->booking_no,
            "currency_id" => $salesOrder->currency_id,
            "team_leader" => $salesOrder->team_leader,
            "receive_date" => $salesOrder->receive_date,
            "booking_date" => $salesOrder->booking_date,
            "booking_type" => $salesOrder->booking_type,
            "delivery_date" => $salesOrder->delivery_date,
            "sales_order_no" => $salesOrder->sales_order_no,
            "supplier_id" => $salesOrder->unitData['id'],
            "supplier_value" => $salesOrder->unitData['text'],
            "currency_value" => $salesOrder->currency->currency_name,
            "budget_unique_id" => null,
            "ready_to_approve" => $salesOrder->ready_to_approve,
            "dealing_merchant" => $salesOrder->dealing_merchant,
            "un_approve_request" => $salesOrder->un_approve_request,
            "unapproved_request" => $salesOrder->unapproved_request,
            "fabric_composition" => $salesOrder->fabric_composition,
            'grouped_breakdown' => $salesOrder->grouped_breakdown,
            'booking_type_status' => $salesOrder->booking_type_status,
            'order_status' => $salesOrder->order_status,
            'breakdown' => $salesOrder->breakdown,
        ];
    }

    /**
     * @throws Throwable
     */
    public function delete($id): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $salesOrder = FabricSalesOrder::query()->findOrFail($id);
            $planInfoId = $salesOrder->planInfoMany()->pluck('id')->toArray();
            $isProgramCreated = KnittingProgram::query()->whereIn('plan_info_id', $planInfoId)->exists();

            throw_if($isProgramCreated, new DeleteNotPossibleException("Can't Delete Because of Program Exist."));

            $salesOrder->breakdown()->delete();
            $salesOrder->planInfoMany()->delete();
            $salesOrder->delete();

            DB::commit();
            session()->flash('success', 'Successfully Deleted');
        } catch (\Exception $exception) {
            session()->flash('danger', $exception->getMessage());
        }

        return redirect()->back();
    }

    public function getColorRange(): JsonResponse
    {
        $colorRange = ColorRange::all('id', 'name as text');
        return response()->json($colorRange, 200);
    }

    public function getFabricBooking(Request $request): JsonResponse
    {
        $unitId = $request->input('unit_id');
        $buyerId = $request->input('buyer_id');
        $searchBy = $request->input('search_by');
        $bookingNo = $request->input('booking_no');
        $bookingEndDate = $request->input('end_date');
        $bookingStartDate = $request->input('start_date');
        $umoId = optional(UnitOfMeasurement::query()
            ->where('unit_of_measurement', 'kg')
            ->first())->id;
        $process = Process::query()
            ->where('process_name', 'LIKE', "%Knitting%")
            ->first();
        $colorRange = ColorRange::query()
            ->where('name', 'LIKE', "%Average Color%")
            ->first();

        $data = SalesOrderBookingSearch::for($searchBy)
            ->setBuyerId($buyerId)
            ->setUnitId($unitId)
            ->setBookingStartDate($bookingStartDate)
            ->setBookingEndDate($bookingEndDate)
            ->setBookingNo($bookingNo)
            ->setUMOId($umoId)
            ->setProcessId($process)
            ->setColorRange($colorRange)
            ->response();
        return response()->json($data, 200);
    }

    public function getView(Request $request, $id)
    {
        $fabricSalesOrder = FabricSalesOrder::query()
            ->with([
                'currency',
                'factory',
                'buyerData:id,name',
                'breakdown.bodyPart',
                'breakdown.fabricColor',
                'breakdown.colorType',
                'breakdown.programUOM',
                'breakdown.process'
            ])
            ->findOrFail($id);
        $data['salesOrder'] = $fabricSalesOrder;
        $data['digit'] = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        if (request('pdf')) {
            return PDF::loadView('knitting::fabricSalesOrder.pdf-v2', $data)
                ->setOrientation('landscape')
                ->stream('fabric_sales_order_report.pdf');
        }

        if (request('excel')) {
            return Excel::download(new FabricSalesOrderViewV2Export($data), 'fabric-sales-order-view-v2.xlsx');
        }

        return view('knitting::fabricSalesOrder.view-v2', $data);

    }
}
