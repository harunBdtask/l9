<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 12:49 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use SkylarkSoft\GoRMG\Merchandising\Models\Country;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderItemDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class PurchaseOrderBreakdownAjax
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get()
    {
        $ratio = '';
        $order_id = $this->request->order_id;
        $order = Order::find($order_id);
        $po_qty = PurchaseOrder::where('order_id', $this->request->order_id)->get()->sum('po_quantity');
        $data['color'] = Color::pluck('name', 'id');
        $data['size'] = Size::pluck('name', 'id');
        $data['country'] = Country::pluck('name', 'id');
        $data['details_data'] = OrderItemDetail::with('item')->where('order_id', $order_id)->get();
        $data['items_list'] = OrderItemDetail::with('item')->where('order_id', $order_id)->where('factory_id', Auth::user()->factory_id)->get()->pluck('item.item_name', 'item.id');
        $view = View::make('merchandising::purchase-order.partial', $data);
        $ratio .= '<h6 style="padding-left: 10px">Set Ratio</h6>';
        foreach ($data['details_data']->unique('item_id') as $key => $items) {
            $ratio .= '<div class="col-md-3">
                        <label for="" class="">' . $items->item->item_name . '<dfn>*</dfn></label>
                        <input type="hidden" name="item_id_label[]" value="' . $items->item->item_name . '" >
                        <input type="hidden" name="item_id[]" value="' . $items->item->id . '" class="form-control form-control-sm" id="item_id" >
                        <input type="text" name="ratio[]" class="form-control form-control-sm ratio" id="ratio_' . $key . '" readonly="true">
                      </div>';
        }
        $quantity = $order->total_quantity - $po_qty;
        $response = "$view##$ratio##$quantity";

        return $response;
    }
}
