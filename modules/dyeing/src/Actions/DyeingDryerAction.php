<?php

namespace SkylarkSoft\GoRMG\Dyeing\Actions;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Dryer\Dryer;

class DyeingDryerAction
{

    public function storeDetails(Dryer $dryer, $dryerDetails)
    {
        $dryer->dryerDetails()->createMany($dryerDetails);
    }

    public function updateDetails(Dryer $dryer, $dryerDetails)
    {
        foreach ($dryerDetails as $detail) {
            $dryer->dryerDetails()->updateOrCreate([
                'id' => $detail['id']
            ], $detail);
        }
    }

}
