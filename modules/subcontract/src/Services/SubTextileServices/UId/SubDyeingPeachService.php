<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\UId;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingPeach\SubDyeingPeach;

class SubDyeingPeachService
{
    public static function generateUniqueId(): string
    {
        $prefix = SubDyeingPeach::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'SDP-' . date('y') . '-' . $generate;
    }
}
