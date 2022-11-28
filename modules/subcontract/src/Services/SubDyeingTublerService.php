<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingTubeCompacting;

class SubDyeingTublerService
{
    public static function generateUniqueId(): string
    {
        $prefix = SubDyeingTubeCompacting::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'SDTL-' . date('y') . '-' . $generate;
    }
}
