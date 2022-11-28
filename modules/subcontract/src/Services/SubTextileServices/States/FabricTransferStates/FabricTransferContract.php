<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\States\FabricTransferStates;

use Illuminate\Http\Request;

interface FabricTransferContract
{
    public function handle(Request $request);
}
