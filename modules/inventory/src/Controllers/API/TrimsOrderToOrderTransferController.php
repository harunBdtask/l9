<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsOrderToOrderTransfer;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceive;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStockSummery;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsOrderToOrderTransferRequest;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use Throwable;

class TrimsOrderToOrderTransferController extends Controller
{
    public $response = [];
    private $status = 200;

    public function getReceiveDetails(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
        ]);

        $storeId = $request->get('store_id');
        $buyerId = $request->get('buyer_id');
        $uniqueId = $request->get('unique_id');
        $styleName = $request->get('style_name');
        $poNo = $request->get('po_no');
        $fromShipmentDate = $request->get('from_shipment_date');
        $toShipmentDate = $request->get('to_shipment_date');

        return TrimsReceive::query()->with([
            'details' => function ($query) use ($fromShipmentDate, $toShipmentDate, $uniqueId, $styleName, $poNo) {
                $query->when($fromShipmentDate && $toShipmentDate,
                    function ($query) use ($fromShipmentDate, $toShipmentDate) {
                        $query->wherebetween('ship_date', [$fromShipmentDate, $toShipmentDate]);
                    })
                    ->when($uniqueId, function ($query) use ($uniqueId) {
                        $query->where('order_uniq_id', $uniqueId);
                    })->when($styleName, function ($query) use ($styleName) {
                        $query->where('style_name', $styleName);
                    })->when($poNo, function ($query) use ($poNo) {
                        $query->whereJsoncontains('po_no', $poNo);
                    });
            }])
            ->where('store_id', $storeId)
            ->when($buyerId, function ($query) use ($buyerId) {
                $query->where('buyer_id', $buyerId);
            })->get()->map(function ($receive) {

                $styles = collect($receive->details)->pluck('style_name')->unique();
                $purchaseOrders = collect($receive->details)->pluck('po_no')->flatten()->unique();
                $poQty = PurchaseOrder::query()->whereIn('po_no', $purchaseOrders)->sum('po_quantity');

                return [
                    'id' => $receive->id,
                    'uniq_id' => $receive->uniq_id,
                    'receive_basic' => $receive->receive_basic,
                    'factory_id' => $receive->factory_id,
                    'factory_name' => $receive->factory->factory_name,
                    'year' => Carbon::create($receive->receive_date)->format('Y'),
                    'store_id' => $receive->store_id,
                    'store_name' => $receive->store->name,
                    'receive_date' => $receive->receive_date,
                    'challan_no' => $receive->challan_no,
                    'supplier_id' => $receive->supplier_id,
                    'supplier_name' => $receive->supplier->name,
                    'buyer_id' => $receive->buyer_id,
                    'buyer_name' => $receive->buyer->name,
                    'styles' => $styles,
                    'style_qty' => '',
                    'po' => $purchaseOrders,
                    'po_qty' => $poQty,
                    'details' => $receive->details->groupBy(['item_id'])->map(function ($details) use ($receive) {
                        $shipDate = collect($details)->max('ship_date');
                        $detail = $details->first();
                        $stockSummery = TrimsStockSummery::query()->where('style_name', $detail->style_name)
                            ->where('item_id', $detail->item_id)
                            ->where('uom_id', $detail->uom_id)
                            ->first();
                        $receiveQty = (float)$stockSummery->balance;
                        $rate = (float)number_format($stockSummery->receive_amount / $stockSummery->receive_qty, 4);

                        return [
                            'id' => $detail->id,
                            'buyer_name' => $receive->buyer->name,
                            'order_uniq_id' => $detail->order_uniq_id,
                            'order_qty' => 0,
                            'style_name' => $detail->style_name,
                            'uniq_id' => $detail->uniq_id,
                            'item_id' => $detail->item_id,
                            'item_name' => $detail->trimsItem->item_group,
                            'item_description' => $detail->item_description,
                            'ship_date' => $shipDate,
                            'po_no' => $detail->po_no,
                            'uom_id' => $detail->uom_id,
                            'uom_name' => $detail->uom->unit_of_measurement,
                            'current_stock' => $receiveQty,
                            'transfer_qty' => 0,
                            'rate' => $rate,
                        ];
                    })
                ];
            });
    }

    public function index()
    {
        $trimsOrderToOrderTransfer = TrimsOrderToOrderTransfer::query()->orderByDesc('id')->get();
        return view('inventory::trims.pages.trims_order_to_order_transfer_index', [
            'trimsOrderToOrderTransfer' => $trimsOrderToOrderTransfer,
        ]);
    }

    public function create()
    {
        return view('inventory::trims.trims-order-transfer');
    }

    /**
     * @throws Throwable
     */
    public function store(TrimsOrderToOrderTransferRequest $request, TrimsOrderToOrderTransfer $trimsOrderToOrderTransfer): JsonResponse
    {
        try {
            DB::beginTransaction();
            $fromOrder = $request->get('from_order');
            $toOrder = $request->get('to_order');
            $transferMeta = collect($fromOrder)->only('style_name');

            $fromStockSummery = TrimsStockSummery::query()->where('style_name', $fromOrder['style_name'])
                ->where('item_id', $fromOrder['item_id'])
                ->where('uom_id', $fromOrder['uom_id'])
                ->first();

            $toStockSummery = TrimsStockSummery::query()->where('style_name', $toOrder['style_name'])
                ->where('item_id', $toOrder['item_id'])
                ->where('uom_id', $toOrder['uom_id'])
                ->first();

            $balanceQty = $fromStockSummery->balance - $fromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $fromOrder['rate'];
            $fromStockSummery->update([
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
                'transfer' => $fromOrder['transfer_qty'],
                'transfer_meta' => json_encode($transferMeta),
            ]);

            $receiveAmount = $fromOrder['transfer_qty'] * $fromOrder['rate'];
            $balanceQty = $toStockSummery->balance + $fromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $fromOrder['rate'];
            $toStockSummery->update([
                'receive_qty' => $toStockSummery->receive_qty + $fromOrder['transfer_qty'],
                'receive_amount' => $toStockSummery->receive_amount + $receiveAmount,
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
            ]);

            $trimsOrderToOrderTransfer->fill($request->all())->save();
            DB::commit();

            $this->response['message'] = ApplicationConstant::S_CREATED;
            $this->status = Response::HTTP_CREATED;
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->response['message'] = $exception->getMessage();
            $this->response['line'] = $exception->getLine();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }

    public function show(TrimsOrderToOrderTransfer $trimsOrderToOrderTransfer): JsonResponse
    {
        return response()->json($trimsOrderToOrderTransfer, $this->status);
    }

    /**
     * @throws Throwable
     */
    public function update(TrimsOrderToOrderTransferRequest $request, TrimsOrderToOrderTransfer $trimsOrderToOrderTransfer): JsonResponse
    {
        try {
            DB::beginTransaction();
            $fromOrder = $request->get('from_order');
            $previousFromOrder = $trimsOrderToOrderTransfer->from_order;
            $toOrder = $request->get('to_order');
            $previousToOrder = $trimsOrderToOrderTransfer->to_order;
            $transferMeta = collect($fromOrder)->only('style_name');

            $previousFromStockSummery = TrimsStockSummery::query()->where('style_name', $previousFromOrder['style_name'])
                ->where('item_id', $previousFromOrder['item_id'])
                ->where('uom_id', $previousFromOrder['uom_id'])
                ->first();

            $previousToStockSummery = TrimsStockSummery::query()->where('style_name', $previousToOrder['style_name'])
                ->where('item_id', $previousToOrder['item_id'])
                ->where('uom_id', $previousToOrder['uom_id'])
                ->first();

            $balanceQty = $previousFromStockSummery->balance + $previousFromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $previousFromOrder['rate'];
            $previousFromStockSummery->update([
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
                'transfer' => $previousFromStockSummery['transfer'] - $previousFromOrder['transfer_qty'],
            ]);

            $receiveAmount = $previousFromOrder['transfer_qty'] * $previousFromOrder['rate'];
            $balanceQty = $previousToStockSummery->balance - $previousFromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $previousFromOrder['rate'];
            $previousToStockSummery->update([
                'receive_qty' => $previousToStockSummery->receive_qty - $previousFromOrder['transfer_qty'],
                'receive_amount' => $previousToStockSummery->receive_amount - $receiveAmount,
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
            ]);

            $fromStockSummery = TrimsStockSummery::query()->where('style_name', $fromOrder['style_name'])
                ->where('item_id', $fromOrder['item_id'])
                ->where('uom_id', $fromOrder['uom_id'])
                ->first();

            $toStockSummery = TrimsStockSummery::query()->where('style_name', $toOrder['style_name'])
                ->where('item_id', $toOrder['item_id'])
                ->where('uom_id', $toOrder['uom_id'])
                ->first();

            $balanceQty = $fromStockSummery->balance - $fromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $fromOrder['rate'];
            $fromStockSummery->update([
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
                'transfer' => $fromOrder['transfer_qty'],
                'transfer_meta' => json_encode($transferMeta),
            ]);

            $receiveAmount = $fromOrder['transfer_qty'] * $fromOrder['rate'];
            $balanceQty = $toStockSummery->balance + $fromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $fromOrder['rate'];
            $toStockSummery->update([
                'receive_qty' => $toStockSummery->receive_qty + $fromOrder['transfer_qty'],
                'receive_amount' => $toStockSummery->receive_amount + $receiveAmount,
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
            ]);

            $trimsOrderToOrderTransfer->fill($request->all())->save();
            DB::commit();

            $this->response['message'] = ApplicationConstant::S_CREATED;
            $this->status = Response::HTTP_CREATED;
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->response['message'] = $exception->getMessage();
            $this->response['line'] = $exception->getLine();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }

    /**
     * @throws Throwable
     */
    public function destroy(TrimsOrderToOrderTransfer $trimsOrderToOrderTransfer): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $fromOrder = $trimsOrderToOrderTransfer->from_order;
            $toOrder = $trimsOrderToOrderTransfer->to_order;
            $fromStockSummery = TrimsStockSummery::query()->where('style_name', $fromOrder['style_name'])
                ->where('item_id', $fromOrder['item_id'])
                ->where('uom_id', $fromOrder['uom_id'])
                ->first();

            $balanceQty = $fromStockSummery->balance + $fromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $fromOrder['rate'];
            $transfer = $fromStockSummery->transfer - $fromOrder['transfer_qty'];
            $fromStockSummery->update([
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
                'transfer' => $transfer,
            ]);

            $toStockSummery = TrimsStockSummery::query()->where('style_name', $toOrder['style_name'])
                ->where('item_id', $toOrder['item_id'])
                ->where('uom_id', $toOrder['uom_id'])
                ->first();

            $receiveQty = $toStockSummery->receive_qty - $fromOrder['transfer_qty'];
            $receiveAmount = $fromOrder['transfer_qty'] * $fromOrder['rate'];
            $balanceQty = $toStockSummery->balance - $fromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $fromOrder['rate'];
            $toStockSummery->update([
                'receive_qty' => $receiveQty,
                'receive_amount' => $toStockSummery->receive_amount - $receiveAmount,
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
            ]);

            $trimsOrderToOrderTransfer->delete();
            DB::commit();
            Session::flash('success', 'Data Deleted successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            Session::flash('error', "Something went wrong!{$exception->getMessage()}");
        }

        return redirect()->back();
    }
}
