<?php

namespace SkylarkSoft\GoRMG\DyesStore;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SkylarkSoft\GoRMG\Inventory\Events\DyesTransactionCompleted;
use SkylarkSoft\GoRMG\Inventory\Listeners\DyesGenerateBarcode;

class DsInventoryEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        DyesTransactionCompleted::class => [
            DyesGenerateBarcode::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
