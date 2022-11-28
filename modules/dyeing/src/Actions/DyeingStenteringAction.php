<?php

namespace SkylarkSoft\GoRMG\Dyeing\Actions;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Stentering\Stentering;

class DyeingStenteringAction
{
    public function storeDetails(Stentering $stentering,$stenteringDetails)
    {
        $stentering->stenteringDetails()->createMany($stenteringDetails);
    }

    public function updateDetails(Stentering $stentering,$stenteringDetails)
    {
        foreach ($stenteringDetails as $detail) {
            $stentering->stenteringDetails()->updateOrCreate([
                'id' => $detail['id']
            ],$detail);
        }
    }
}