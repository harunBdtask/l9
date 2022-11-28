<?php

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Models\Country;
use SkylarkSoft\GoRMG\Merchandising\Models\IncotermPlace;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderItemDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderRatioBreakdown;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Incoterm;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Models\TeamMemberAssign;

class PurchaseOrderEditFormLoad
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function load()
    {
        $purchase_order_id = $this->request->purchase_order_id ?? '';
        $data['shipping_mode'] = SHIPMENT_MODE;
        $data['packing_mode'] = PACKING_MODE;
        $data['buyer'] = Buyer::withoutGlobalScope('factoryId')->pluck('name', 'id');
        $data['color'] = Color::pluck('name', 'id');
        $data['size'] = Size::pluck('name', 'id');
        $data['country'] = Country::pluck('name', 'id');
        $data['incoterm'] = Incoterm::pluck('incoterm', 'id');
        $data['order_style_no'] = Order::pluck('order_style_no', 'id');
        $data['purchase_order'] = PurchaseOrder::find($purchase_order_id);
        $data['incoterm_places'] = IncotermPlace::pluck('incoterm_place', 'id');
        $data['item_details'] = PurchaseOrderDetail::with('item', 'color', 'size', 'countries')->where('purchase_order_id', $data['purchase_order']->id)->get();
        $has_ratio_details = PurchaseOrderRatioBreakdown::with('item')->where('purchase_order_id', $purchase_order_id)->get();
        if ($has_ratio_details->count() > 0) {
            $data['ratio_details'] = $has_ratio_details;
        } else {
            $ratio = OrderItemDetail::with('item')->where('order_id', $data['purchase_order']->order_id)->get()->unique('item_id');
            $data['ratio_details'] = $ratio->unique('item_id');
        }
        $data['item_details_direct'] = OrderItemDetail::with('item')->where('order_id', $data['purchase_order']->order_id)->get()->unique('item_id');

        return $data;
    }

    public function ableToUpdate()
    {
        $purchase_order = PurchaseOrder::with('order')->find($this->request->purchase_order_id);
        $team = TeamMemberAssign::where('member_id', $purchase_order->order->dealing_merchant)->first()->team_id ?? null;
        $teamMembers = TeamMemberAssign::where('team_id', $team)->pluck('member_id')->toArray() ?? [];
        if (in_array(Auth::id(), $teamMembers) || (getRole() == 'admin' || getRole() == 'super-admin')) {
            return true;
        }

        return false;
    }
}
