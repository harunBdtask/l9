<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingRecipe\DyeingRecipe;

class DyeingRecipeFormatter
{

    public function format(DyeingRecipe $dyeingRecipe): array
    {
        $dyeingRecipe->load('buyer', 'recipeDetails', 'subDyeingBatch.machineAllocations');

        $orderNos = collect($dyeingRecipe->getRelation('subDyeingBatch')->orders_no)
            ->implode(', ');

        $machines = collect($dyeingRecipe->getRelation('subDyeingBatch')->machineAllocations)
            ->pluck('machine.name')
            ->implode(',');

        return array_merge($dyeingRecipe->toArray(), [
            'orders_no' => $orderNos,
            'machines_no' => $machines,
            'yarn_description' => null,
            'buyer' => $dyeingRecipe->getRelation('buyer')->name,
            'machine_capacity' => $dyeingRecipe->getRelation('subDyeingBatch')->total_machine_capacity,
            'fabric_description' => $dyeingRecipe->getRelation('subDyeingBatch')->fabric_description,
            'fabric_weight' => $dyeingRecipe->getRelation('subDyeingBatch')->total_batch_weight,
            'color' => $dyeingRecipe->getRelation('subDyeingBatch')->fabric_color_id,
            'ld_no' => $dyeingRecipe->getRelation('subDyeingBatch')->ld_no,
            'gsm' => $dyeingRecipe->getRelation('subDyeingBatch')->gsm,
            'recipe_details' => $dyeingRecipe->getRelation('recipeDetails')
                ->map(function ($collection) {
                    return (new DyeingRecipeDetailFormatter())->format($collection);
                }),
        ]);
    }
}
