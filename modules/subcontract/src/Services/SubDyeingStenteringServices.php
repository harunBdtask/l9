<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingStentering;

class SubDyeingStenteringServices
{
    public static function generateUniqueId(): string
    {
        $prefix = SubDyeingStentering::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'SDST-' . date('y') . '-' . $generate;
    }
}
