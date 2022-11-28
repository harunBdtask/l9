<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;

class SubTextileOrderService
{
    public static function generateUniqueId(): string
    {
        $prefix = SubTextileOrder::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'STOE-' . date('y') . '-' . $generate;
    }
}
