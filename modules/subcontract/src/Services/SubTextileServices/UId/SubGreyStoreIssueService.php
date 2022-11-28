<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\UId;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreIssue;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;

class SubGreyStoreIssueService
{
    public static function generateChallanNo($orderId): string
    {
        $orderNo = SubTextileOrder::query()->where('id', $orderId)->first()->order_no ?? '';
        $prefix = SubGreyStoreIssue::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return 'CH-' . $orderNo . '-' . $generate;
    }
}
