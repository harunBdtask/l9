<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Services\V3\UID\TrimsStoreReceiveReturn;

use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceiveReturn\TrimsStoreReceiveReturn;

class TrimsStoreReceiveReturnService
{
    public static function generateUniqueId(): string
    {
        $prefix = TrimsStoreReceiveReturn::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'TSRR-' . date('y') . '-' . $generate;
    }
}
