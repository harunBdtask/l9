<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/24/19
 * Time: 3:27 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Order;

use SkylarkSoft\GoRMG\Merchandising\Models\OrderItemDetail;

class OrderItemDetailDelete
{
    private $order_id;

    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    public function handle()
    {
        $is_delete = OrderItemDetail::where('order_id', $this->order_id)->delete();

        return $is_delete;
    }
}
