<?php

namespace SkylarkSoft\GoRMG\Merchandising\Listeners;

use SkylarkSoft\GoRMG\Merchandising\Events\OrderWiseBudgetUpdate;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\BudgetUpdateServices\FabricUpdateService;

class FabricUpdateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(OrderWiseBudgetUpdate $event)
    {
        FabricUpdateService::update($event->orderId);
    }
}
