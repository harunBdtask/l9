<?php

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Order;

use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;

class ColorWiseSummaryOfOder
{
    private $orderId;

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    public function get()
    {
        if (! $this->orderId) {
            return false;
        }

        $with = [
            'agent:id,buying_agent_name',
            'team:id,team_name',
            'dealing_merchants:id,first_name,last_name',
            'buyer:id,name',
            'get_items.item:id,item_name',
          //  'get_items.',
            'purchase_orders.po_details.item',
            'purchase_orders.po_details.color',
            'purchase_orders.po_details.size',
            'purchase_orders.po_details.countries',
            'purchase_orders.po_details.color_types:id,color_types',
            'purchase_orders.uom',
            'purchase_orders.breakdown_ratio.item:id,item_name',
            'purchase_orders.incoterms',
            'purchase_orders.incoterm_placeses',
        ];

        $order = Order::withoutGlobalScope('factoryId')
            ->with($with)
            ->find($this->orderId);

        if (! $order) {
            return false;
        }

        $purchase_order_details = PurchaseOrderDetail::withoutGlobalScope('factoryId')
            ->whereIn('purchase_order_id', $order->purchase_orders->pluck('id')->all())
            ->orderBy('color_id')
            ->orderBy('size_id')
            ->get();

        return [
            'order' => $order,
            'purchase_order_details' => $purchase_order_details,
        ];
    }
}
