<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 12:46 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class ValidateIfPurchaseOrderExists
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function validate()
    {
        $buyerId = $this->request->buyer_id;
        $purchase_orderId = $this->request->po_id;
        $order_id = $this->request->order_id;
        $po_exits = PurchaseOrder::where('buyer_id', $buyerId)
            ->where('order_id', $order_id)
            ->where('po_no', $purchase_orderId)->get();
        $value = collect($po_exits)->count();

        return $value;
    }
}
