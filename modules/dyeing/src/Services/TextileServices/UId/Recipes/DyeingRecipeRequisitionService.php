<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId\Recipes;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingRecipe\DyeingRecipeRequisition;

class DyeingRecipeRequisitionService
{

    public static function generateUniqueId(): string
    {
        $prefix = DyeingRecipeRequisition::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'DRR-' . date('y') . '-' . $generate;
    }
}
