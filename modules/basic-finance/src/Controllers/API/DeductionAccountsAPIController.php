<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\BasicFinance\Services\FetchLeafNodesCodeService;

class DeductionAccountsAPIController extends Controller
{
    public function __invoke()
    {
        $response = (new FetchLeafNodesCodeService('5303000000000', '5303001000000'))->handle();
        if ($response['data'] && count($response['data'])) {
            $response['data'] = collect($response['data'])->map(function($account) {
                return [
                    'bf_account_id' => $account['id'],
                    'bf_account' => $account['bf_account'],
                    'amount_usd' => '',
                    'con_rate' => '',
                    'amount_bdt' => '',
                    'percentage' => '',
                ];
            });
        }
        
        return \response()->json($response, $response['status']);
    }
}