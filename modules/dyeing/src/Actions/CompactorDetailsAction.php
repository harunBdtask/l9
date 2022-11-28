<?php

namespace SkylarkSoft\GoRMG\Dyeing\Actions;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Compactor\Compactor;

class CompactorDetailsAction
{

    public function storeDetails(Compactor $compactor, $compactorDetails)
    {
        $compactor->compactorDetails()->createMany($compactorDetails);
    }

    public function updateDetails(Compactor $compactor, $compactorDetails)
    {
        foreach ($compactorDetails as $detail) {
            $compactor->compactorDetails()->updateOrCreate([
                'id' => $detail['id']
            ], $detail);
        }
    }

}
