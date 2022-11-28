<?php

namespace SkylarkSoft\GoRMG\Merchandising\Observers;

use Illuminate\Support\Facades\Cache;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class BuyerObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Buyer  $buyer
     * @return void
     */
    public function created(Buyer $buyer)
    {
        $this->updateBuyerCache();
    }

    /**
     * Handle the user "updated" event.
     *
     * @param Buyer $buyer
     * @return void
     */
    public function updated(Buyer $buyer)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Buyer  $buyer
     * @return void
     */
    public function deleted(Buyer $buyer)
    {
        $this->updateBuyerCache();
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Buyer  $buyer
     * @return void
     */
    public function restored(Buyer $buyer)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Buyer  $buyer
     * @return void
     */
    public function forceDeleted(Buyer $buyer)
    {
        //
    }

    /**
     * cache value update
     */
    public function updateBuyerCache()
    {
        $buyers_count = Buyer::count();
        Cache::put('buyers_count', $buyers_count, 1440);

        return true;
    }
}
