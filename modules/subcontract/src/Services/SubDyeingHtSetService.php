<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingHtSet;

class SubDyeingHtSetService
{
    public static function generateUniqueId(): string
    {
        $prefix = SubDyeingHtSet::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'HTS-' . date('y') . '-' . $generate;
    }
}
