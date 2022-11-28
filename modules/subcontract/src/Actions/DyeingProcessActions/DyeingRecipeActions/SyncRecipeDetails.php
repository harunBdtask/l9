<?php

namespace SkylarkSoft\GoRMG\Subcontract\Actions\DyeingProcessActions\DyeingRecipeActions;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipe;

class SyncRecipeDetails
{
    public function syncTotalQty(SubDyeingRecipe $dyeingRecipe)
    {
        foreach ($dyeingRecipe->recipeDetails as $recipeDetail) {
            if ($recipeDetail->percentage != null) {
                $recipeDetail->update([
                    'total_qty' => $this->calculateWithPercentage($dyeingRecipe, $recipeDetail),
                ]);
            }
            if ($recipeDetail->g_per_ltr != null) {
                $recipeDetail->update([
                    'total_qty' => $this->calculateWithGLtr($dyeingRecipe, $recipeDetail),
                ]);
            }
        }
    }

    /**
     * @param $dyeingRecipe
     * @param $recipeDetail
     * @return float|int
     */
    private function calculateWithPercentage($dyeingRecipe, $recipeDetail)
    {
        $totalQtyFormula = ($dyeingRecipe->subDyeingBatch->total_batch_weight * ($recipeDetail->percentage / 100)) ?? 0;
        $totalQtyFormula += (($totalQtyFormula * $recipeDetail->plus_minus / 100) + $recipeDetail->additional) ?? 0;

        return $totalQtyFormula;
    }

    /**
     * @param $dyeingRecipe
     * @param $recipeDetail
     * @return float|int
     */
    private function calculateWithGLtr($dyeingRecipe, $recipeDetail)
    {
        $totalQtyFormula = (($dyeingRecipe->total_liq_level * $recipeDetail->g_per_ltr) / 1000) ?? 0;
        $totalQtyFormula += (($totalQtyFormula * $recipeDetail->plus_minus / 100) + $recipeDetail->additional) ?? 0;

        return $totalQtyFormula;
    }
}
