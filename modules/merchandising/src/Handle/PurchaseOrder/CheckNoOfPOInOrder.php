<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 3:30 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class CheckNoOfPOInOrder
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get()
    {
        $order_data = Order::find($this->request->order_id);
        $no_of_po = $order_data->total_po;
        $get_all_po_by_order = PurchaseOrder::where('order_id', $this->request->order_id)
            ->where('id', '!=', $this->request->id)
            ->get();
        return $get_all_po_by_order->count() >= $no_of_po;
    }
}
