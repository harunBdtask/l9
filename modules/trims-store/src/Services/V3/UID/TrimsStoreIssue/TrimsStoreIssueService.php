<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Services\V3\UID\TrimsStoreIssue;

use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreIssue\TrimsStoreIssue;

class TrimsStoreIssueService
{
    public static function generateUniqueId(): string
    {
        $prefix = TrimsStoreIssue::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'TSI-' . date('y') . '-' . $generate;
    }
}
