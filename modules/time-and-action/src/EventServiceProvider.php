<?php

namespace SkylarkSoft\GoRMG\TimeAndAction;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SkylarkSoft\GoRMG\TimeAndAction\Events\TNAReportProcess;
use SkylarkSoft\GoRMG\TimeAndAction\Listeners\ReportProcessFromOrder;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TNAReportProcess::class => [
            ReportProcessFromOrder::class,
        ],
    ];
}
