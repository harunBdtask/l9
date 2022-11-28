<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/24/19
 * Time: 3:51 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Order;

use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Handle\Order\Interfaces\OrderInterface;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderColorBreakdown;

class OrderColorBreakdownStore implements OrderInterface
{
    private $request;
    private $order_id;

    public function __construct($request, $order)
    {
        $this->request = $request;
        $this->order_id = $order->id;
    }

    public function handle()
    {
        /* order color breakdown save */
        foreach ($this->request->color_ids as $key => $value) {
            $color = new OrderColorBreakdown();
            $color->order_id = $this->order_id;
            $color->color_id = $value;
            $color->save();
        }
    }
}
