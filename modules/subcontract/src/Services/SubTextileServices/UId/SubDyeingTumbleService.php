<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\UId;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingTumble\SubDyeingTumble;

class SubDyeingTumbleService
{
    public static function generateUniqueId(): string
    {
        $prefix = SubDyeingTumble::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'SDT-' . date('y') . '-' . $generate;
    }
}
