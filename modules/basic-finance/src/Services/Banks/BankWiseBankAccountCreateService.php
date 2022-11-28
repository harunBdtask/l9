<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Banks;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\BasicFinance\Models\Bank;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;

class BankWiseBankAccountCreateService
{
    /**
     * @param Request $request
     * @return HigherOrderBuilderProxy|mixed
     */
    public static function store(Request $request)
    {
        $bankAccount = Bank::query()->with('account.childAcs')->findOrFail($request->get('bank_id'));
        $totalAccounts = Account::query()->where('parent_ac', $bankAccount->account_id)->count() + 1;
        $code = rtrim($bankAccount->account->code, 0) . str_pad($totalAccounts, 3, 0, STR_PAD_LEFT);
        $code = str_pad($code, 13, 0, STR_PAD_RIGHT);

        $account = Account::query()->create([
            'name' => $request->get('bank_short_name') . '-' . $request->get('account_type_short_name') . '-' . $request->get('account_number'),
            'code' => $code,
            'type_id' => $bankAccount->account->type_id,
            'parent_ac' => $bankAccount->account_id,
            'is_editable' => 1,
            'is_transactional' => 1,
            'is_active' => 1,
        ]);

        return $account->id;
    }

    public static function update(Request $request)
    {
        $account = Account::query()->findOrFail($request->get('account_id'));
        $account->name = $request->get('bank_short_name') . '-'. $request->get('account_type_short_name') . '-'  . $request->get('account_number');
        $account->save();
    }
}
