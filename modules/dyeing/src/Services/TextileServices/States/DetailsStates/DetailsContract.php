<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\States\DetailsStates;

use Illuminate\Http\Request;

interface DetailsContract
{

    public function handle(Request $request);
}
