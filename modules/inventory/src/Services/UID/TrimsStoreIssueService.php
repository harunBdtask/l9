<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\UID;

use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreIssue\TrimsStoreIssue;

class TrimsStoreIssueService
{
    public static function generateUniqueId(): string
    {
        $prefix = TrimsStoreIssue::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'TSI-' . date('y') . '-' . $generate;
    }
}
