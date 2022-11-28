<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\UID;

use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventory;

class TrimsInventoryService
{
    public static function generateUniqueId(): string
    {
        $prefix = TrimsInventory::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'TSI-' . date('y') . '-' . $generate;
    }
}
