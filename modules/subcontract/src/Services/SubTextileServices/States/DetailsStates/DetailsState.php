<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\States\DetailsStates;

class DetailsState
{
    const SUB_DYEING_BATCH_DETAILS = 1;
    const SUB_DYEING_ORDER_DETAILS = 2;

    public static function setState($type)
    {
        $types = [
            self::SUB_DYEING_BATCH_DETAILS => new SubDyeingBatchBasis(),
            self::SUB_DYEING_ORDER_DETAILS => new SubDyeingOrderBasis(),
        ];

        return collect($types)->get($type);
    }
}
