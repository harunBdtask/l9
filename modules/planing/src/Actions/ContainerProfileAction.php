<?php

namespace SkylarkSoft\GoRMG\Planing\Actions;

use SkylarkSoft\GoRMG\Planing\Models\ContainerProfile\ContainerProfile;

class ContainerProfileAction
{
    public function storeDetails(ContainerProfile $containerProfile, $details)
    {
        $containerProfile->details()->createMany($details);
    }

    public function updateDetails(ContainerProfile $containerProfile, $details)
    {
        foreach ($details as $detail) {
            $containerProfile->details()->updateOrCreate([
                'id' => $detail['id'],
            ], $detail);
        }
    }

    public function destroyDetails(ContainerProfile $containerProfile)
    {
        $containerProfile->details()->delete();
    }
}
