<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 3:33 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class ValidatePOQtyExceed
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function validate()
    {
        $order_qty = Order::find($this->request->order_id)->total_quantity;
        $get_all_quantity_by_order = PurchaseOrder::where('order_id', $this->request->order_id)->get()->sum('po_quantity');
        $get_current_po_qty = PurchaseOrder::where('id', $this->request->id)->first();
        $extra_qty = $this->request->po_quantity - ($get_current_po_qty ? $get_current_po_qty->po_quantity : 0);
        if (($get_all_quantity_by_order + $extra_qty) > $order_qty) {
            return true;
        }

        return false;
    }
}
