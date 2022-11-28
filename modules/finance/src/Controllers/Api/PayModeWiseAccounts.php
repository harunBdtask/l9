<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use Symfony\Component\HttpFoundation\Response;

class PayModeWiseAccounts extends Controller
{
    public function __invoke(int $payMode): JsonResponse
    {
        try {
            $accounts = Account::query()
                ->has('accountInfo.parentAccount')
                ->has('accountInfo.groupAccount')
                ->has('accountInfo.controlAccount')
//                ->when($payMode == 1, function (Builder $query) {
//                    $query->whereNotNull('bank_account_id')
//                        ->where('account_type', Account::SUB_LEDGER);
//                })->when($payMode == 2, function (Builder $query) {
//                    $query->whereNull('bank_account_id');
//                })
                ->get()->map(function ($account) {

                    if ($account->accountInfo->ledgerAccount->name === "N\\A") {
                        $name = $account->name;
                    } else {
                        $name = "{$account->accountInfo->ledgerAccount->name} ( {$account->name} )";
                    }

                    return [
                        'id' => $account->id,
                        'text' => $name,
                        'name' => $account->name,
                        'code' => $account->code,
                        'bank_account_id' => $account->bank_account_id,
                    ];
                });

            return response()->json($accounts, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
