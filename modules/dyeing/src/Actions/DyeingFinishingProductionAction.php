<?php

namespace SkylarkSoft\GoRMG\Dyeing\Actions;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingFinishingProduction\DyeingFinishingProduction;

class DyeingFinishingProductionAction
{
    public function storeDetails(DyeingFinishingProduction $dyeingFinishingProduction,
                                                           $finishingProductionDetails)
    {
        $dyeingFinishingProduction->finishingProductionDetails()
            ->createMany($finishingProductionDetails);
    }

    public function updateDetails(DyeingFinishingProduction $dyeingFinishingProduction,
                                                            $finishingProductionDetails)
    {
        foreach ($finishingProductionDetails as $detail) {
            $dyeingFinishingProduction->finishingProductionDetails()->updateOrCreate([
                'id' => $detail['id']
            ], $detail);
        }
    }
}
