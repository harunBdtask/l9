<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\Request;
use Session;
use SkylarkSoft\GoRMG\Iedroplets\Models\Shipment;
use SkylarkSoft\GoRMG\Iedroplets\PackageConst;
use SkylarkSoft\GoRMG\Merchandising\Actions\StyleAuditReportAction;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class ShipmentController extends Controller
{

    public function index()
    {
        $shipment_list = Shipment::with([
            'buyer:id,name',
            'order:id,style_name',
            'purchaseOrder:id,po_no'
        ])
            ->orderBy('id', 'desc')
            ->paginate();

        return view(PackageConst::PACKAGE_NAME . '::pages.shipment_list', [
            'shipment_list' => $shipment_list
        ]);
    }

    public function create()
    {
        $buyers = Buyer::pluck('name', 'id')->all();

        return view(PackageConst::PACKAGE_NAME . '::forms.shipment_create')->with('buyers', $buyers);
    }


    public function getShipmentStatusUpdateForm($purchase_order_id, $color_id)
    {
        $order_wise_shipment = PurchaseOrderDetail::where([
            'purchase_order_id' => $purchase_order_id,
            'color_id' => $color_id
        ])->with('size')->get();

        foreach ($order_wise_shipment as $key => $shipment) {
            $order_wise_shipment[$key]->ship_quantity = Shipment::where([
                'order_id' => $purchase_order_id,
                'color_id' => $color_id,
                'size_id' => $shipment->size_id
            ])->sum('ship_quantity');
            $order_wise_shipment[$key]->size_name = $shipment->size->name;
        }

        return $order_wise_shipment;
    }

    public function getShipmentPoInformation($order_id)
    {
        $purchase_order_data = PurchaseOrder::where('order_id', $order_id)
            ->select(['id', 'po_no', 'po_quantity'])
            ->get();

        foreach ($purchase_order_data as $key => $order) {
            $purchase_order_data[$key]->ship_quantity = Shipment::where('purchase_order_id', $order->id)->sum('ship_quantity');
        }
        return $purchase_order_data;
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'buyer_id' => 'required',
            'order_id' => 'required',
            'purchase_order_id.*' => 'required',
            'ship_quantity.*' => 'nullable|integer',
            'short_reject_qty.*' => 'nullable|integer'
        ]);
        $buyer_id = $request->get('buyer_id');
        $order_id = $request->get('order_id');
        $purchase_order_ids = $request->get('purchase_order_id');
        $ship_quantities = $request->get('ship_quantity');
        $short_reject_qtys = $request->get('short_reject_qty');
        $remarks = $request->get('remarks');

        $current_dat_time = date('Y/m/d h:i:s', time());
        $insert_update_rows = count($purchase_order_ids);
        $insertData = [];

        for ($i = 0; $i < $insert_update_rows; $i++) {
            if ($ship_quantities[$i] <= 0) {
                continue;
            }
            $insertData[] = [
                'buyer_id' => $buyer_id,
                'order_id' => $order_id,
                'purchase_order_id' => $purchase_order_ids[$i],
                'ship_quantity' => $ship_quantities[$i],
                'short_reject_qty' => $short_reject_qtys[$i] ?? 0,
                'remarks' => $remarks[$i],
                'user_id' => userId(),
                'factory_id' => factoryId(),
                'created_at' => $current_dat_time,
                'updated_at' => $current_dat_time,
            ];
        }

        try {
            DB::beginTransaction();
            Shipment::insert($insertData);

            (new StyleAuditReportAction())->init($order_id)->handleOrder()
                ->handleProduction()->handleShipment()->saveOrUpdate();

            DB::commit();
            Session::flash('success', S_UPDATE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', 'Please form fill up correctly');
        } finally {
            return redirect()->back();
        }
    }

    public function searchShipments(Request $request)
    {
        $shipment_list = Shipment::withoutGlobalScope('factoryId')
            ->with([
                'buyer:id,name',
                'order:id,booking_no',
                'purchaseOrder:id,po_no'
            ])
            ->join('buyers', 'shipments.buyer_id', 'buyers.id')
            ->join('orders', 'shipments.order_id', 'orders.id')
            ->join('purchase_orders', 'shipments.purchase_order_id', 'purchase_orders.id')
            ->where('shipments.factory_id', factoryId())
            ->where(function ($query) use ($request) {
                $query->orWhere('buyers.name', 'like', '%' . $request->q . '%')
                    ->orWhere('orders.booking_no', 'like', '%' . $request->q . '%')
                    ->orWhere('purchase_orders.po_no', 'like', '%' . $request->q . '%');
            })
            ->orderBy('shipments.id', 'desc')
            ->paginate();

        return view(PackageConst::PACKAGE_NAME . '::pages.shipment_list', [
            'shipment_list' => $shipment_list
        ]);
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        try {
            Shipment::destroy($id);
            Session::flash('success', S_DELETE_MSG);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        } finally {
            return redirect()->back();
        }
    }


    /**
     * For TNA
     * @param $order_id
     * @return array
     */
    public static function getOrderWiseActualShipmentDateInfo($order_id): array
    {
        $shipQuery = Shipment::where(['order_id' => $order_id]);
        $actual_start = '';
        $actual_end = '';
        $duration = '';
        if ($shipQuery->count()) {
            $order_qty = Order::findOrFail($order_id)->total_quantity;
            $firstShipment = $shipQuery->orderBy('created_at', 'asc')->first();
            $actual_start = date('Y-m-d', strtotime($firstShipment->created_at));
            $shipQueryClone = clone $shipQuery;
            $totalShipment = $shipQueryClone->sum('ship_quantity') - $shipQueryClone->sum('short_reject_qty');
            $lastShipment = $shipQueryClone->orderBy('created_at', 'desc')->first();
            if ($totalShipment >= $order_qty) {
                $actual_end = date('Y-m-d', strtotime($lastShipment->created_at));
                $duration = calculateDays($actual_start, $actual_end);
            }
        }

        return [
            'actual_start' => $actual_start,
            'actual_end' => $actual_end,
            'actual_duration' => $duration,
        ];
    }

}
