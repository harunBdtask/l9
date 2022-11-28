<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\UId;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingFinishingProduction\SubDyeingFinishingProduction;

class SubDyeingFinishingProductionService
{
    public static function generateUniqueId(): string
    {
        $prefix = SubDyeingFinishingProduction::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'SDFP-' . date('y') . '-' . $generate;
    }
}
