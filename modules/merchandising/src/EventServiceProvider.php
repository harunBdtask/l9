<?php

namespace SkylarkSoft\GoRMG\Merchandising;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SkylarkSoft\GoRMG\Merchandising\Events\OrderWiseBudgetUpdate;
use SkylarkSoft\GoRMG\Merchandising\Events\StyleAuditReportUpdate;
use SkylarkSoft\GoRMG\Merchandising\Listeners\EmbellishmentUpdateListener;
use SkylarkSoft\GoRMG\Merchandising\Listeners\FabricUpdateListener;
use SkylarkSoft\GoRMG\Merchandising\Listeners\StyleAuditReportUpdateListener;
use SkylarkSoft\GoRMG\Merchandising\Listeners\TrimsUpdateListener;
use SkylarkSoft\GoRMG\Merchandising\Listeners\WashUpdateListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderWiseBudgetUpdate::class => [
            FabricUpdateListener::class,
            TrimsUpdateListener::class,
            EmbellishmentUpdateListener::class,
            WashUpdateListener::class,
        ]
    ];
}
