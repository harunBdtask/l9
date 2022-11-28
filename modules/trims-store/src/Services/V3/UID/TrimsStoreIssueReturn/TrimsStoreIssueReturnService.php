<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Services\V3\UID\TrimsStoreIssueReturn;

use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreIssueReturn\TrimsStoreIssueReturn;

class TrimsStoreIssueReturnService
{
    public static function generateUniqueId(): string
    {
        $prefix = TrimsStoreIssueReturn::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'TSIR-' . date('y') . '-' . $generate;
    }
}
