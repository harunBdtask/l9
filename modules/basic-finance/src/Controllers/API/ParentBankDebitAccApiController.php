<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\Models\Bank;
use SkylarkSoft\GoRMG\BasicFinance\Models\BankAccount;
use Symfony\Component\HttpFoundation\Response;

class ParentBankDebitAccApiController extends Controller
{
    public function __invoke($bankAccountId): JsonResponse
    {
        try {
            $bankAccountId = (int)$bankAccountId;
            $parentAcc = BankAccount::query()->select('bank_id', 'id')
                ->where('account_id', $bankAccountId)
                ->where('status', '1')->get();

            if ($parentAcc) {
                $account1 = Bank::query()
                    ->whereIn('id', collect($parentAcc)->pluck('bank_id'))
                    ->get()->map(function ($account) {
                        return [
                            'id' => $account->id,
                            'account_id' => $account->account_id,
                        ];
                    });
            }

            $account2 = Account::query()
                ->whereIn('id', collect($account1)->pluck('account_id'))
                ->get()->map(function ($account) {
                    return [
                        'text' => $account->name,
                        'name' => $account->name,
                    ];
                });
            $account['id'] = (int)(collect($account1)->pluck('id')->implode(', '));
            $account['text'] = collect($account2)->pluck('name')->implode(', ');
            $account['name'] = collect($account2)->pluck('name')->implode(', ');
            $account['bankAccId'] = collect($parentAcc)->pluck('id')->implode(', ');
            return response()->json($account, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
