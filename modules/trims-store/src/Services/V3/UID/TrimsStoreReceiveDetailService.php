<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Services\V3\UID;

use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceive\TrimsStoreReceiveDetail;

class TrimsStoreReceiveDetailService
{
    public static function generateUniqueId(): string
    {
        $prefix = TrimsStoreReceiveDetail::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'TSRD-' . date('y') . '-' . $generate;
    }
}
