<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId\Recipes;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingRecipe\DyeingRecipe;

class DyeingRecipeService
{

    public static function generateUniqueId(): string
    {
        $prefix = DyeingRecipe::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'DR-' . date('y') . '-' . $generate;
    }
}
