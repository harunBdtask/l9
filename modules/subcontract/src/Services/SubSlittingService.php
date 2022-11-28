<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubSlitting;

class SubSlittingService
{
    public static function generateUniqueId(): string
    {
        $prefix = SubSlitting::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'SD-' . date('y') . '-' . $generate;
    }
}
