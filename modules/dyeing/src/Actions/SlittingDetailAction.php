<?php

namespace SkylarkSoft\GoRMG\Dyeing\Actions;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Slitting\Slitting;

class SlittingDetailAction
{

    /**
     * @param Slitting $slitting
     * @param $slittingDetails
     * @return void
     */
    public function storeDetails(Slitting $slitting, $slittingDetails)
    {
        $slitting->slittingDetails()->createMany($slittingDetails);
    }

    /**
     * @param Slitting $slitting
     * @param $slittingDetails
     * @return void
     */
    public function updateDetails(Slitting $slitting, $slittingDetails)
    {
        foreach ($slittingDetails as $detail) {
            $slitting->slittingDetails()->updateOrCreate([
                'id' => $detail['id'],
            ], $detail);
        }
    }

}
