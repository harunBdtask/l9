<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\States\DetailsStates;

class DetailsState
{
    const DYEING_BATCH_DETAILS = 1;
    const DYEING_ORDER_DETAILS = 2;

    public static function setState($type)
    {
        $types = [
            self::DYEING_BATCH_DETAILS => new DyeingBatchBasis(),
            self::DYEING_ORDER_DETAILS => new DyeingOrderBasis(),
        ];

        return collect($types)->get($type);
    }
}
