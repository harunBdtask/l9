<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 11:06 AM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Order;

use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class OrderDetailWithPO
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get()
    {
        $order_id = $this->request->order;
        if (! $order_id) {
            return false;
        }
        $data = [];
        $initial_loaders = [
            'agent:id,buying_agent_name',
            'team:id,team_name',
            'dealing_merchants:id,first_name,last_name,screen_name',
            'currency:id,currency_name',
        ];
        $order = Order::withoutGlobalScope('factoryId')->with($initial_loaders)->find($order_id);
        if (! $order) {
            return false;
        }

        if ($order) {
            $data['basic_info'] = unserialize(serialize($order));
            $order = $order->load([
                'buyer:id,name',
                'get_items.item:id,item_name',
                'get_items.fabric_composition',
                'purchase_orders.po_details.item',
                'purchase_orders.po_details.color',
                'purchase_orders.po_details.size',
                'purchase_orders.po_details.countries',
                'purchase_orders.po_details.color_types:id,color_types',
                'purchase_orders.uom',
                'purchase_orders.breakdown_ratio.item:id,item_name',
                'purchase_orders.incoterms',
                'purchase_orders.incoterm_placeses',
            ]);
            $data['buyer'] = $order['buyer'];
            $data['items'] = $order['get_items'];
            $data['orders'] = $order['purchase_orders'];
        }

        return $data;
    }
}
