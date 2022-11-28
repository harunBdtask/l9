<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\UID;

use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrr;

class TrimsStoreMrrService
{
    public static function generateUniqueId(): string
    {
        $prefix = TrimsStoreMrr::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'TSM-' . date('y') . '-' . $generate;
    }
}
