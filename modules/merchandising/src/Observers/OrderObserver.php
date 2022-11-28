<?php

namespace SkylarkSoft\GoRMG\Merchandising\Observers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class OrderObserver
{
    /**
     * Handle the order "created" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        $this->updateOrdersCache();
    }

    /**
     * Handle the order "updated" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        $this->updateOrdersCache();
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        $this->updateOrdersCache();
    }

    /**
     * Handle the order "restored" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }

    /**
     * cache value update
     */
    public function updateOrdersCache()
    {
        $orders_count = Order::count();
        $orders = Order::whereYear('created_at', Carbon::now()->year)
            ->select(
                'id',
                'total_quantity',
                DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, DATE(created_at) as date')
            )->get();

        Cache::put('orders_count', $orders_count, 1440);
        Cache::put('orders', $orders, 1440);

        return true;
    }
}
