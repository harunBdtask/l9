<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipeRequisition;

class SubTextileRecipeRequisitionService
{
    public static function generateUniqueId(): string
    {
        $dyeingRecipe = request()->route('dyeingRecipe');
        $prefix = SubDyeingRecipeRequisition::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'SDRR-' . date('y') . '-' . $dyeingRecipe->id . '-' . $generate;
    }
}
