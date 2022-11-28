<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters;

class DyeingRecipeDetailFormatter
{

    public function format($dyeingRecipeDetail)
    {
        return array_merge($dyeingRecipeDetail->toArray(), [
            'unit_of_measurement' => $dyeingRecipeDetail->unitOfMeasurement->name,
        ]);
    }
}
