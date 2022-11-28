<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\States\FabricTransferStates;

class FabricTransferState
{
    const RECEIVE_BASIS = 1;
    const ISSUE_BASIS = 2;

    public function setState($type)
    {
        $types = [
            self::RECEIVE_BASIS => new ReceiveBasis(),
            self::ISSUE_BASIS => new IssueBasis(),
        ];

        return collect($types)->get($type);
    }
}
