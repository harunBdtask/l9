<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 1:22 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class PurchaseOrderListAjax
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get()
    {
        $request = $this->request;
        $purchaseOrders = PurchaseOrder::where('buyer_id', $this->request->buyer_id)
            ->with('purchaseOrderDetails')
            ->where('order_id', $this->request->order_id)
            ->get()
            ->filter(function ($item) use ($request) {
                if ($request->has('color_id')) {
                    return $item->purchaseOrderDetails->contains('color_id', $request->color_id);
                }

                return true;
            })
            ->all();

        return $purchaseOrders;
    }
}
