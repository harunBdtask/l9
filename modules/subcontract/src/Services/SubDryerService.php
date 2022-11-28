<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDryer;

class SubDryerService
{
    public static function generateUniqueId(): string
    {
        $prefix = SubDryer::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'SD-' . date('y') . '-' . $generate;
    }
}
