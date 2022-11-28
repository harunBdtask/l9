<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 1:53 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class PurchaseOrderUtilityColors
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get()
    {
        $request = $this->request;
        $colors = PurchaseOrder::with(['colors' => function ($quert) {
            $quert->withoutGlobalScope('factoryId');
        }])
            //->where('buyer_id', $request->buyer_id)
            ->where('order_id', $request->order_id)
            ->get()
            ->map(function ($item) use ($request) {
                return $item->colors;
            })
            ->flatten()
            ->unique('id')
            ->values()
            ->all();

        return $colors;
    }
}
