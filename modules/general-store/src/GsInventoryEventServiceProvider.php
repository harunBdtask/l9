<?php

namespace SkylarkSoft\GoRMG\GeneralStore;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SkylarkSoft\GoRMG\GeneralStore\Events\TransactionCompleted;
use SkylarkSoft\GoRMG\GeneralStore\Listeners\GenerateBarcode;

class GsInventoryEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TransactionCompleted::class => [
            GenerateBarcode::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
