<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\BasicFinance\Services\FetchLeafNodesService;

class DistributionAccountsAPIController extends Controller
{
    public function __invoke()
    {
        $response = (new FetchLeafNodesService('Cash at Bank'))->handle();

        return \response()->json($response, $response['status']);
    }
}