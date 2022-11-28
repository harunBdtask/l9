<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 2:05 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class PurchaseOrderDetails
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function getDetails()
    {
        $poId = $this->request->purchase_order_id;
        if (! $poId) {
            return false;
        }
        $loaders = [
            'buyer', 'uom', 'breakdown_ratio', 'po_details.item',
            'po_details.color', 'po_details.size',
            'po_details.country', 'order.dealing_merchants',
            'order.team_leaders', 'incoterms', 'incoterm_placeses',
        ];
        $data['purchase_order'] = PurchaseOrder::with($loaders)->find($poId);

        return $data;
    }
}
