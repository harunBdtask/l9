<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/24/19
 * Time: 3:48 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Order;

use SkylarkSoft\GoRMG\Merchandising\Handle\Order\Interfaces\OrderInterface;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderItemDetail;

class OrderItemDetailsStore implements OrderInterface
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
        // Insert in order item details table
        foreach ($this->request->item_id as $key => $value) {
            $order_item_details = new OrderItemDetail();
            $order_item_details->order_id = $this->order_id;
            $order_item_details->item_id = $this->request->item_id[$key];
            $order_item_details->item_category = $this->request->item_category[$key];
            $order_item_details->item_description = $this->request->item_description[$key];
            $order_item_details->fabric_description = $this->request->fabric_description[$key];
            $order_item_details->composition_fabric_id = $this->request->composition_fabric_id[$key];
            $order_item_details->fabrication = get_fabrication_name($this->request->composition_fabric_id[$key]);
            $order_item_details->gsm = $this->request->gsm[$key];
            $order_item_details->unit_price = $this->request->unit_price[$key];
            $order_item_details->quantity = $this->request->quantity[$key];
            $order_item_details->save();
        }
    }
}
