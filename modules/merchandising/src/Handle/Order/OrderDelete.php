<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 9:58 AM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Order;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderColorBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderItemDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderSizeBreakdown;

class OrderDelete
{
    private $order_id;

    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    public function handle()
    {
        try {
            DB::beginTransaction();
            Order::find($this->order_id)->delete();
            OrderItemDetail::where('order_id', $this->order_id)->delete();
            OrderColorBreakdown::where('order_id', $this->order_id)->delete();
            OrderSizeBreakdown::where('order_id', $this->order_id)->delete();
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();

            return false;
        }
    }

    public function ableToDelete()
    {
        $data['order'] = DB::table('orders')->find($this->order_id);
        if ($data['order']->created_by == Auth::id() || (getRole() == 'admin' || getRole() == 'super-admin')) {
            return true;
        }

        return false;
    }
}
