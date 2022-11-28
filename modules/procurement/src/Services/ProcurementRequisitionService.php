<?php

namespace SkylarkSoft\GoRMG\Procurement\Services;

use SkylarkSoft\GoRMG\Procurement\Models\ProcurementRequisition;

class ProcurementRequisitionService
{
    public static function generateUniqueId(): string
    {
        $prefix = ProcurementRequisition::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'PR-' . date('y') . '-' . $generate;
    }
}
