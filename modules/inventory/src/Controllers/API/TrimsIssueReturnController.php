<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssue;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssueReturn;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssueReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsIssueReturnRequest;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class TrimsIssueReturnController extends Controller
{
    public $response = [];
    public $status = 200;

    const ISSUE_ID = 'issue_id';
    const CHALLAN_NO = 'challan_no';

    public function getIssueDetails(Request $request)
    {
        $fromDate = $request->get('fromDate');
        $toDate = $request->get('toDate');
        $type = $request->get('type');
        $uniqueId = $request->get('uniqueId');

        return TrimsIssue::query()->with(['details' => function ($query) {
            $query->select('*', DB::raw('sum(issue_qty) as total_issue_qty,COUNT(*) as total_details'))
                ->groupBy('style_name', 'po_no', 'item_id', 'item_color', 'item_size', 'uom_id', 'floor', 'room', 'rack', 'shelf', 'bin');
        }])->whereBetween('issue_date', [$fromDate, $toDate])
            ->when($uniqueId, function ($query) use ($type, $uniqueId) {
                $query->when($type === self::ISSUE_ID, function ($query) use ($uniqueId) {
                    $query->where('uniq_id', $uniqueId);
                })->when($type === self::CHALLAN_NO, function ($query) use ($uniqueId) {
                    $query->where('issue_challan_no', $uniqueId);
                });
            })->get()->map(function ($issue) {
                return [
                    'id' => $issue->id,
                    'uniq_id' => $issue->uniq_id,
                    'year' => Carbon::createFromFormat('Y-m-d', $issue->issue_date)->format('Y'),
                    'issue_basis' => $issue->issue_basis,
                    'booking_no' => '',
                    'issue_challan_no' => $issue->issue_challan_no,
                    'store_id' => $issue->store_id,
                    'store' => $issue->store->name,
                    'issue_date' => $issue->issue_date,
                    'details' => $issue->details->map(function ($detail) {
                        $previousReturnQty = TrimsIssueReturnDetail::query()->where('style_name', $detail->style_name)
                            ->whereJsonContains('po_no', $detail->po_no)
                            ->where('item_id', $detail->item_id)
                            ->where('uom_id', $detail->uom_id)
                            ->where('item_color', $detail->item_color)
                            ->where('item_size', $detail->item_size)
                            ->where('floor', $detail->floor)
                            ->where('room', $detail->room)
                            ->where('rack', $detail->rack)
                            ->where('shelf', $detail->shelf)
                            ->where('bin', $detail->bin)
                            ->sum('return_qty');

                        return [
                            'id' => null,
                            'trims_issue_details_id' => $detail->id,
                            'uniq_id' => $detail->uniq_id,
                            'trims_issue_id' => $detail->trims_issue_id,
                            'total_details' => $detail->total_details,
                            'po_no' => $detail->po_no,
                            'style_name' => $detail->style_name,
                            'item_id' => $detail->item_id,
                            'uom_id' => $detail->uom_id,
                            'item_name' => $detail->item->item_name,
                            'item_description' => $detail->item_description,
                            'item_color' => $detail->item_color,
                            'item_size' => $detail->item_size,
                            'floor' => $detail->floor,
                            'floor_name' => $detail->floorDetail->name,
                            'room' => $detail->room,
                            'room_name' => $detail->roomDetail->name,
                            'rack' => $detail->rack,
                            'rack_name' => $detail->rackDetail->name,
                            'shelf' => $detail->shelf,
                            'shelf_name' => $detail->shelfDetail->name,
                            'bin' => $detail->bin,
                            'bin_name' => $detail->binDetail->name,
                            'issue_qty' => $detail->issue_qty,
                            'total_issue_qty' => $detail->total_issue_qty,
                            'rate' => $detail->rate,
                            'amount' => $detail->rate * $detail->issue_qty,
                            'buyer_order' => $detail->po_no,
                            'cumulative_return' => $previousReturnQty,
                            'net_used' => $detail->total_issue_qty - $previousReturnQty,
                        ];
                    }),
                ];
            });
    }

    public function getIssueDetailsWisePoDetails(Request $request)
    {
        $trimsIssueId = $request->get('trims_issue_id');
        $poNo = $request->get('po_no');
        $itemId = $request->get('item_id');
        $styleName = $request->get('style_name');
        $itemColor = $request->get('item_color');
        $itemSize = $request->get('item_size');

        return TrimsIssueDetail::query()->where('trims_issue_id', $trimsIssueId)
            ->whereJsonContains('po_no', $poNo)
            ->where('item_id', $itemId)
            ->where('style_name', $styleName)
            ->where('item_color', $itemColor)
            ->where('item_size', $itemSize)
            ->get()->map(function ($detail) {
                $purchaseOrder = PurchaseOrder::query()->whereIn('po_no', $detail->po_no)->get();
                $lastDate = collect($purchaseOrder)->max('ex_factory_date');
                $totalPoQty = collect($purchaseOrder)->sum('po_quantity');

                // Previous total issue return quantity find.
                $previousIssueReturn = TrimsIssueReturnDetail::query()
                    ->where('trims_issue_details_id', $detail->id)
                    ->sum('return_qty');

                return [
                    'po_no' => $detail->po_no,
                    'shipment_date' => $lastDate,
                    'po_qty' => $totalPoQty,
                    'return_qty' => '',
                    'issue_qty' => $detail->issue_qty - $previousIssueReturn,
                ];
            });
    }

    public function index()
    {
        $trimsIssueReturns = TrimsIssueReturn::query()->orderByDesc('id')->paginate();

        return view('inventory::trims.pages.trims-issue-returns', compact('trimsIssueReturns'));
    }

    public function create()
    {
        return view('inventory::trims.trims-issue-return');
    }

    public function store(TrimsIssueReturnRequest $request, TrimsIssueReturn $trimsIssueReturn): JsonResponse
    {
        try {
            DB::beginTransaction();
            $trimsIssueReturn->fill($request->all())->save();
            $trimsIssueReturn->details()->createMany($request->get('details'));
            DB::commit();

            $this->response['message'] = ApplicationConstant::S_CREATED;
            $this->status = Response::HTTP_CREATED;
        } catch (\Exception $exception) {
            DB::rollBack();

            $this->response['message'] = $exception->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }
}
