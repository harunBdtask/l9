<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\UId;

use SkylarkSoft\GoRMG\BasicFinance\Models\Procurements\ProcurementRequisition;

class ProcurementRequisitionService
{

    public static function generateUniqueId(): string
    {
        $prefix = ProcurementRequisition::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'PR-' . date('y') . '-' . $generate;
    }
}
