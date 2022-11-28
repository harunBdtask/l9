<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Services\V3\UID\TrimsStoreReceiveReturn;

use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceiveReturn\TrimsStoreReceiveReturnDetail;

class TrimsStoreReceiveReturnDetailService
{
    public static function generateUniqueId(): string
    {
        $prefix = TrimsStoreReceiveReturnDetail::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'TSRRD-' . date('y') . '-' . $generate;
    }
}
