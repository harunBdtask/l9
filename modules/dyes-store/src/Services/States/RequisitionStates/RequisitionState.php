<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services\States\RequisitionStates;

class RequisitionState
{
    public static function setState($type)
    {
        $implementors = [
            2 => new SubContractBasisRequisitions(),
            3 => new DyeingBasisRequisitions(),
        ];

        return $implementors[$type];
    }
}
