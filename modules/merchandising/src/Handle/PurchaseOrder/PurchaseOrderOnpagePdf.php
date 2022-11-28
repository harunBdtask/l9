<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 2:53 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class PurchaseOrderOnpagePdf
{
    public function generate()
    {
        $user = Auth::user();
        $query = PurchaseOrder::with('buyer', 'order', 'order.dealing_merchants');
        get_lists_data_team_wise($user, $query);
        $data['purchase_order_list'] = $query->orderBy('id', 'desc')->paginate();

        return $data;
    }
}
