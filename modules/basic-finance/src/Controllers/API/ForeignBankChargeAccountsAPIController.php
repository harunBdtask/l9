<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\BasicFinance\Services\FetchLeafNodesCodeService;

class ForeignBankChargeAccountsAPIController extends Controller
{
    public function __invoke()
    {
        $response = (new FetchLeafNodesCodeService('5303001000000'))->handle();
        if ($response['data'] && count($response['data'])) {
            $data = $response['data'];
            $converted_data = [];
            collect($data)->where('code', '!=', '5303001009000')->map(function($account) use(&$converted_data){
                $converted_data[] = [
                    'bf_account_id' => $account['id'],
                    'bf_account' => $account['bf_account'],
                    'amount_usd' => '',
                    'con_rate' => '',
                    'amount_bdt' => '',
                    'percentage' => '',
                ];
            });
            $added_data = [];
            collect($data)->where('code', '5303001009000')->map(function($account) use(&$added_data){
                $added_data = [
                    'bf_account_id' => $account['id'],
                    'bf_account' => $account['bf_account'],
                    'amount_usd' => '',
                    'con_rate' => '',
                    'amount_bdt' => '',
                    'percentage' => '',
                ];
            });
            \array_push($converted_data, $added_data);
            $response['data'] = $converted_data;
        }
        
        return \response()->json($response, $response['status']);
    }
}