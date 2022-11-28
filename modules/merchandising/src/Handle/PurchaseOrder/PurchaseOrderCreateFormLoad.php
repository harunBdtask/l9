<?php

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Country;
use SkylarkSoft\GoRMG\Merchandising\Models\IncotermPlace;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderItemDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Incoterm;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class PurchaseOrderCreateFormLoad
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function load()
    {
        $order_id = $this->request->order_id ?? null;
        $get_all_quantity_by_order = PurchaseOrder::where('order_id', $this->request->order_id)->get()->sum('po_quantity');
        $data['purchase_order'] = null;
        $data['buyer'] = Buyer::withoutGLobalScope('factoryId')->pluck('name', 'id');
        $data['order_style_no'] = DB::table('orders')->where('factory_id', factoryId())->pluck('order_style_no', 'id');
        $data['shipping_mode'] = SHIPMENT_MODE;
        $data['packing_mode'] = PACKING_MODE;
        $data['items_list'] = OrderItemDetail::with('item')->where('order_id', $order_id)->where('factory_id', Auth::user()->factory_id)->get()->pluck('item.item_name', 'item.id');
        $data['color'] = Color::pluck('name', 'id');
        $data['size'] = Size::pluck('name', 'id');
        $data['country'] = Country::pluck('name', 'id');
        $data['incoterm'] = Incoterm::pluck('incoterm', 'id');
        $data['incoterm_places'] = IncotermPlace::pluck('incoterm_place', 'id');
        if (isset($order_id)) {
            $query = Order::find($order_id);
            $data['selected_buyer'] = $query->buyer_id ?? null;
            $data['total_po'] = $query->total_po ?? null;
            $data['selected_styles'] = $query->id ?? null;
            $data['left_quantity'] = ($query->total_quantity - $get_all_quantity_by_order);
            $data['item_details_direct'] = OrderItemDetail::with('item')->where('order_id', $order_id)->get()->unique('item_id');
        }

        return $data;
    }
}
