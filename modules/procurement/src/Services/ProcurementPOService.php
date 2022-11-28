<?php

namespace SkylarkSoft\GoRMG\Procurement\Services;

use SkylarkSoft\GoRMG\Procurement\Models\ProcurePurchaseOrder;

class ProcurementPOService
{
    public static function generateUniqueId(): string
    {
        $prefix = ProcurePurchaseOrder::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'PO-' . date('y') . '-' . $generate;
    }
}
