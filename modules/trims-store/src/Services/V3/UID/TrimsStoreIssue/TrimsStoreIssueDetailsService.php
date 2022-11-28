<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Services\V3\UID\TrimsStoreIssue;

use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreIssue\TrimsStoreIssueDetail;

class TrimsStoreIssueDetailsService
{
    public static function generateUniqueId(): string
    {
        $prefix = TrimsStoreIssueDetail::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'TSID-' . date('y') . '-' . $generate;
    }
}
