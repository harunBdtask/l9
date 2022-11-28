<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\UID;

use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventory;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceive;

class TrimsStoreReceiveService
{
    public static function generateUniqueId(): string
    {
        $prefix = TrimsStoreReceive::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'TSR-' . date('y') . '-' . $generate;
    }
}
