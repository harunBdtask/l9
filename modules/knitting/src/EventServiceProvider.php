<?php

namespace SkylarkSoft\GoRMG\Knitting;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SkylarkSoft\GoRMG\Knitting\Events\FabricBooking;
use SkylarkSoft\GoRMG\Knitting\Listeners\DeleteFabricBooking;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        FabricBooking::class => [
            DeleteFabricBooking::class,
        ],
    ];
}
