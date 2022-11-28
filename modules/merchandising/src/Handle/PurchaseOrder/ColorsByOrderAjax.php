<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 1:42 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use Symfony\Component\HttpFoundation\Response;

class ColorsByOrderAjax
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get()
    {
        $purchaseOrderId = $this->request->purchase_order_ids;
        if (is_array($purchaseOrderId)) {
            $sqlQuery = PurchaseOrderDetail::where('quantity', '>', 0)
                ->with('color:id,name')
                ->whereIn('purchase_order_id', $purchaseOrderId);
        } else {
            $sqlQuery = PurchaseOrderDetail::where('quantity', '>', 0)
                ->with('color:id,name')
                ->where('purchase_order_id', $purchaseOrderId);
        }
        $colors = $sqlQuery->get()->map(function ($item) {
            return $item->color;
        })->unique('id')->values()->all();

        return response()->json($colors, Response::HTTP_OK);
    }
}
