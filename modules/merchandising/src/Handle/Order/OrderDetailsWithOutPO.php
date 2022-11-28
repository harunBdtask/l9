<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 10:50 AM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Order;

use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class OrderDetailsWithOutPO
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
            'incoterm:id,incoterm',
            'incoterm_place:id,incoterm_place',
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
            $after_loaders = [
                'buyer:id,name',
                'get_items.item:id,item_name',
                'get_items.fabric_composition',
            ];
            $order = $order->load($after_loaders);
            $data['buyer'] = $order['buyer'];
            $data['items'] = $order['get_items'];
        }

        return $data;
    }
}
