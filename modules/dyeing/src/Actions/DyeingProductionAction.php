<?php

namespace SkylarkSoft\GoRMG\Dyeing\Actions;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingProduction\DyeingProduction;

class DyeingProductionAction
{

    public function storeDetails(DyeingProduction $dyeingProduction, $dyeingProductionDetails)
    {
        $dyeingProduction->dyeingProductionDetails()->createMany($dyeingProductionDetails);
    }

    public function updateDetails(DyeingProduction $dyeingProduction, $dyeingProductionDetails)
    {
        foreach ($dyeingProductionDetails as $detail) {
            $dyeingProduction->dyeingProductionDetails()->updateOrCreate([
                'id' => $detail['id'],
            ], $detail);
        }
    }

}
