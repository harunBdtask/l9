<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 3:10 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;

class PurchaseOrderQtyByColorSizeComposition
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function quantity()
    {
        $composition = $this->request->composition;
        $color = $this->request->color;
        $size = $this->request->size;
        $po_id = $this->request->po_id;

        try {
            $data = PurchaseOrderDetail::where([
                'composition_fabric_id' => $composition,
                'color_id' => $color,
            ])->whereIn('purchase_order_id', explode(',', $po_id))->whereIn('size_id', $size)->sum('quantity');
        } catch (\Exception $e) {
            $data = 0;
        }

        return $data;
    }
}
