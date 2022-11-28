<?php

namespace SkylarkSoft\GoRMG\Merchandising\Observers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\OrderItemDetail;

class OrderItemDetailObserver
{
    /**
     * Handle the order item detail "created" event.
     *
     * @param \App\OrderItemDetail $orderItemDetail
     * @return void
     */
    public function created(OrderItemDetail $orderItemDetail)
    {
        $this->updateOrderItemDetailsCache();
    }

    /**
     * Handle the order item detail "updated" event.
     *
     * @param \App\OrderItemDetail $orderItemDetail
     * @return void
     */
    public function updated(OrderItemDetail $orderItemDetail)
    {
        $this->updateOrderItemDetailsCache();
    }

    /**
     * Handle the order item detail "deleted" event.
     *
     * @param \App\OrderItemDetail $orderItemDetail
     * @return void
     */
    public function deleted(OrderItemDetail $orderItemDetail)
    {
        $this->updateOrderItemDetailsCache();
    }

    /**
     * Handle the order item detail "restored" event.
     *
     * @param \App\OrderItemDetail $orderItemDetail
     * @return void
     */
    public function restored(OrderItemDetail $orderItemDetail)
    {
        //
    }

    /**
     * Handle the order item detail "force deleted" event.
     *
     * @param \App\OrderItemDetail $orderItemDetail
     * @return void
     */
    public function forceDeleted(OrderItemDetail $orderItemDetail)
    {
        //
    }

    /**
     * cache value update
     */
    public function updateOrderItemDetailsCache()
    {
        $startMonth = now()->subMonth(6);
        $endMonth = now()->addMonth(6);
        $order_items_details = OrderItemDetail::join('orders', 'order_item_details.order_id', '=', 'orders.id')
            ->whereBetween(
                'orders.order_shipment_date',
                [$startMonth, $endMonth]
            )->orderBy('orders.order_shipment_date', 'asc')
            ->get([
                'orders.id as order_id', 'order_item_details.item_id', 'quantity',
                DB::raw('MONTHNAME(order_shipment_date) order_shipment_date'),
            ])
            ->groupBy('order_shipment_date')->map(function ($item) {
                return $item->sum('quantity');
            });
        Cache::put('order_items_details', $order_items_details, 1440);

        return true;
    }
}
