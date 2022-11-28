<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services\States\RequisitionStates;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingRecipe\DyeingRecipeRequisition;

class DyeingBasisRequisitions implements RequisitionStateContract
{
    public function handle()
    {
        return DyeingRecipeRequisition::query()
            ->get()->map(function ($collection) {
                return [
                    'id' => $collection->unique_id,
                    'text' => $collection->unique_id,
                    'recipe_id' => $collection->dyeing_recipe_id,
                ];
            });
    }
}
