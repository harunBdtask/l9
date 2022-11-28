<?php


namespace SkylarkSoft\GoRMG\Merchandising\Listeners;

use SkylarkSoft\GoRMG\Merchandising\Events\OrderWiseBudgetUpdate;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\BudgetUpdateServices\EmbellishmentUpdateService;

class EmbellishmentUpdateListener
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
        EmbellishmentUpdateService::update($event->orderId);
    }
}
