<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\States\BatchDetailsStates;

use Illuminate\Http\Request;

interface BatchDetailsContract
{
    public function format(Request $request);
}
