<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingSqueezer;

class SubDyeingSqueezerService
{
    public static function generateUniqueId(): string
    {
        $prefix = SubDyeingSqueezer::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'SQZ-' . date('y') . '-' . $generate;
    }
}
