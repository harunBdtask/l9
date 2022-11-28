<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use Symfony\Component\HttpFoundation\Response;

class LedgerAccountsApiController extends Controller
{
    public function __invoke($controlAccountId): JsonResponse
    {
        try {
            $ledgerAccounts = Account::query()
                ->whereRelation('accountInfo', 'control_account_id', $controlAccountId)
                ->where('account_type', Account::LEDGER)
                ->get()->map(function ($account) {
                    return [
                        'id' => $account->id,
                        'text' => "{$account->name} ({$account->code})",
                    ];
                });

            return response()->json($ledgerAccounts, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
