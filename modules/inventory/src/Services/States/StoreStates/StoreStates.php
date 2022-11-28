<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\States\StoreStates;

class StoreStates
{

    const FABRIC_STATE = 1;
    const TRIMS_STATE = 2;

    public static function setState($type)
    {
        $implementors = [
            self::FABRIC_STATE => new FabricStoreBasis(),
            self::TRIMS_STATE => new TrimsStoreBasis(),
        ];

        return $implementors[$type];
    }
}
