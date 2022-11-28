<?php

namespace SkylarkSoft\GoRMG\Dyeing\Actions;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Peach\Peach;

class PeachDetailAction
{

    /**
     * @param Peach $peach
     * @param $peachDetails
     * @return void
     */
    public function storeDetails(Peach $peach, $peachDetails)
    {
        $peach->peachDetails()->createMany($peachDetails);
    }

    /**
     * @param Peach $peach
     * @param $peachDetails
     * @return void
     */
    public function updateDetails(Peach $peach, $peachDetails)
    {
        foreach ($peachDetails as $detail) {
            $peach->peachDetails()->updateOrCreate([
                'id' => $detail['id'],
            ], $detail);
        }
    }

}
