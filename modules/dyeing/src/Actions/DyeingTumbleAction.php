<?php

namespace SkylarkSoft\GoRMG\Dyeing\Actions;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Tumble\Tumble;

class DyeingTumbleAction
{

    public function storeDetails(Tumble $tumble, $tumbleDetails)
    {
        $tumble->tumbleDetails()->createMany($tumbleDetails);
    }

    public function updateDetails(Tumble $tumble, $tumbleDetails)
    {
        foreach ($tumbleDetails as $detail) {
            $tumble->tumbleDetails()->updateOrCreate([
                'id' => $detail['id']
            ], $detail);
        }
    }

}