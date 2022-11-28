<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/24/19
 * Time: 3:53 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Order;

use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Handle\Order\Interfaces\OrderInterface;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderSizeBreakdown;

class OrderSizeBreakdownStore implements OrderInterface
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
        /* order size breakdown save */
        $sorting_no = 1;
        foreach ($this->request->size_ids as $key => $value) {
            $hasData = OrderSizeBreakdown::where(['order_id' => $this->order_id, 'size_id' => ' $value'])->count();
            if ($hasData > 0) {
                continue;
            }
            $color = new OrderSizeBreakdown();
            $color->order_id = $this->order_id;
            $color->size_id = $value;
            $color->short_no = $sorting_no;
            $color->factory_id = Auth::user()->factory_id;
            $color->save();
            $sorting_no++;
        }
    }
}
