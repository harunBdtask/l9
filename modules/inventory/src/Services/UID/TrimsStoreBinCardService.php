<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\UID;

use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreBinCard\TrimsStoreBinCard;

class TrimsStoreBinCardService
{
    public static function generateUniqueId(): string
    {
        $prefix = TrimsStoreBinCard::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'TSBC-' . date('y') . '-' . $generate;
    }
}
