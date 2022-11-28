<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Services\V3\UID;

use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceive\TrimsStoreReceive;

class TrimsStoreReceiveService
{
    public static function generateUniqueId(): string
    {
        $prefix = TrimsStoreReceive::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'TSR-' . date('y') . '-' . $generate;
    }
}
