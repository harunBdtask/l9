<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\UID;

use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreDeliveryChallan\TrimsStoreDeliveryChallan;

class TrimsStoreDeliveryChallanService
{
    public static function generateUniqueId(): string
    {
        $prefix = TrimsStoreDeliveryChallan::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'TSDC-' . date('y') . '-' . $generate;
    }
}
