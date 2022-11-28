<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipe;

class SubTextileRecipeService
{
    public static function generateUniqueId(): string
    {
        $prefix = SubDyeingRecipe::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'SDR-' . date('y') . '-' . $generate;
    }
}
