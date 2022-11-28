<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services\States\RequisitionStates;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipeRequisition;

class SubContractBasisRequisitions implements RequisitionStateContract
{
    public function handle()
    {
        return SubDyeingRecipeRequisition::query()->get()->map(function ($collection) {
            return [
                'id' => $collection->requisition_uid,
                'text' => $collection->requisition_uid,
                'recipe_id' => $collection->sub_dyeing_recipe_id,
            ];
        });
    }
}
