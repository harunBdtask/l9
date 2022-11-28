<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssue;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceive;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsIssueRequest;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class TrimsIssueApiController extends Controller
{
    public $response = [];
    public $status = 200;

    public function index(TrimsIssue $trimsIssue)
    {
        $trimsIssue = $trimsIssue->with('details', 'store')->latest()->paginate(15);
        return view('inventory::trims.pages.trims_issue_index', compact('trimsIssue'));
    }

    public function create()
    {
        return view('inventory::trims.trims-issue');
    }

    public function getReceiveDetails(): JsonResponse
    {
        $buyerId = request('buyer_id');
        $uniqId = request('uniq_id');
        $orderNo = request('order_no');
        $styleName = request('style_name');

        $data = TrimsReceiveDetail::with('trimsReceive.buyer')
            ->when($uniqId, function (Builder $query) use ($uniqId) {

                $query->where('order_uniq_id', $uniqId);

            })->when($orderNo, function (Builder $query) use ($orderNo) {

                $query->whereJsonContains('po_no', collect($orderNo)->values());

            })->when($styleName, function (Builder $query) use ($styleName) {

                $query->where('style_name', $styleName);

            })->when($buyerId, function (Builder $query) use ($buyerId) {

                $query->whereHas('trimsReceive', function (Builder $query) use ($buyerId) {
                    $query->where('buyer_id', $buyerId);
                });

            })
            ->get();

        $responseData = collect($data)->groupBy('style_name')->map(function ($styleWiseData, $styleName) {

            $firstData = $styleWiseData->first();
            $trimsReceive = $firstData->trimsReceive;
            $buyerId = $trimsReceive->buyer_id;
            $buyer = $trimsReceive->buyer->name;
            $purchaseOrderNumbers = collect($styleWiseData)->whereNotNull('po_no')->pluck('po_no')->flatten()->unique()->values();
            $lastPurchaseOrder = PurchaseOrder::whereIn('po_no', $purchaseOrderNumbers)
                ->latest('ex_factory_date')
                ->first();

            $lastShipmentDate = null;

            $order = Order::with('uom')->where('style_name', $styleName)->firstOrFail();

            $orderUOM = $order->uom->unit_of_measurement;

            if ( $lastPurchaseOrder ) {
                $lastShipmentDate = $lastPurchaseOrder->ex_factory_date;
            }

            $poQuantity = PurchaseOrder::whereIn('po_no', $purchaseOrderNumbers)
                ->sum('po_quantity');


            return [
                'buyer'                 => $buyer,
                'buyer_id'              => $buyerId,
                'year'                  => Carbon::parse($lastShipmentDate)->format('Y'),
                'uniq_id'               => $firstData->order_uniq_id,
                'po_no'                 => $purchaseOrderNumbers,
                'po_no_comma_separated' => collect($purchaseOrderNumbers)->implode(', '),
                'po_qty'                => $poQuantity,
                'uom_id'                => $orderUOM,
                'style_name'            => $styleName,
                'shipment_date'         => $lastShipmentDate,
            ];
        })->values();

        return response()->json($responseData);
    }

    public function getReceiveItemDetails(): JsonResponse
    {

        \request()->validate([
            'style_name' => 'required'
        ]);

        $styleName = request('style_name');

        $details = TrimsReceiveDetail::with('trimsReceive', 'uom', 'trimsItem')
            ->where('style_name', $styleName)
            // ->whereJsonContains('po_no', collect($poNo))
            ->get()
            ->groupBy('item_id')
            ->map(function ($itemWiseData, $itemId) use ($styleName) {

                $item = collect($itemWiseData)->first();

                $poNo = collect($itemWiseData)->pluck('po_no')->flatten()->unique()->all();
                $receiveQty = collect($itemWiseData)->sum('receive_qty');

                $prevIssueQty = TrimsIssueDetail::query()
                    ->where('item_id', $itemId)
                    ->where('style_name', $styleName)
                    ->whereJsonContains('po_no', $poNo)
                    ->sum('issue_qty');

                $yetToIssue = $receiveQty - $prevIssueQty;

                $rate = collect($itemWiseData)->avg('rate');

                return [
                    'po_no'            => $poNo,
                    'style_name'       => $styleName,
                    'item_id'          => $itemId,
                    'item_name'        => $item->trimsItem->item_group,
                    'item_description' => $item->item_description,
                    'brand_sup_ref'    => $item->brand_sup_ref,
                    'item_color'       => $item->item_color,
                    'item_size'        => $item->item_size,
                    'uom_id'           => $item->uom_id,
                    'uom'              => $item->uom->unit_of_measurement,
                    'issue_qty'        => $yetToIssue,
                    'receive_qty'      => $receiveQty,
                    'cumul_issue'      => $prevIssueQty,
                    'yet_to_issue'     => $yetToIssue,
                    'stock_qty'        => $yetToIssue,
                    'rate'             => $rate,
                    'floor'            => $item->floor,
                    'floor_name'       => $item->floorDetail->name,
                    'room'             => $item->room,
                    'room_name'        => $item->roomDetail->name,
                    'rack'             => $item->rack,
                    'rack_name'        => $item->rackDetail->name,
                    'shelf'            => $item->shelf,
                    'shelf_name'       => $item->shelfDetail->name,
                    'bin'              => $item->bin,
                    'bin_name'         => $item->binDetail->name,
                    'sewing_line_no'   => $item->sewing_line_no,
                    'order_no'         => collect($poNo)->implode(','),
                ];
            })->values();

        return response()->json($details);
    }


    public function store(TrimsIssueRequest $request): JsonResponse
    {
        $trimsIssueId = $request->get('id');
        $trimsIssue = TrimsIssue::query()->findOrNew($trimsIssueId);

        try {
            DB::beginTransaction();
            $trimsIssue->fill($request->except(['details']))->save();

            if ( $request->get('details') ) {
                foreach ($request->details as $detail) {
                    $trimsIssueDetail = $trimsIssue->details()->findOrNew($detail['id'] ?? null);

                    $trimsIssueDetail->fill($detail)->save();
                }
            }
            DB::commit();
            $this->response['data'] = $trimsIssue;
            $this->response['message'] = ApplicationConstant::S_STORED;

            $this->status = Response::HTTP_CREATED;

        } catch (\Exception $exception) {
            DB::rollBack();
            $this->response['message'] = ApplicationConstant::SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $exception->getMessage();
            $this->status = Response::HTTP_BAD_REQUEST;
        }

        return response()->json($this->response, $this->status);
    }

    public function show(TrimsIssue $trimsIssue): JsonResponse
    {
        $this->response = $trimsIssue->load('details', 'details.uom','details.trimsReceiveDetail.trimsItem');

        return response()->json($this->response);
    }

    public function destroy(TrimsIssue $trimsIssue): RedirectResponse
    {
        try {
            \DB::beginTransaction();
            $trimsIssue->delete();
            $trimsIssue->details()->delete();
            \DB::commit();
            Session::flash('error', 'Data Deleted Successfully');
            $this->response['message'] = ApplicationConstant::S_DELETED;
            return redirect()->back();

        } catch (\Throwable $e) {
            $this->response['message'] = ApplicationConstant::SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $e->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
            return redirect()->back();
        }

        return response()->json($this->response, $this->status);
    }

    public function view($id)
    {
        $trimsIssue = TrimsIssue::query()->with(['details.trimsReceiveDetail', 'details.line', 'details.item', 'details.uom'])->findOrFail($id);
        return view('inventory::trims.issue.view', compact('trimsIssue'));
    }


    public function pdf($id)
    {
        $trimsIssue = TrimsIssue::query()->with(['details.trimsReceiveDetail', 'details.line', 'details.item', 'details.uom'])->findOrFail($id);

        $pdf = PDF::loadView('inventory::trims.issue.pdf',
            compact('trimsIssue'))
            ->setPaper('a4')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('trims_issue.pdf');
    }
}
