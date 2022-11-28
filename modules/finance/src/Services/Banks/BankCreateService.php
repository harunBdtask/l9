<?php

namespace SkylarkSoft\GoRMG\Finance\Services\Banks;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;

class BankCreateService
{
    /**
     * @param Request $request
     * @return mixed
     */
    public static function store(Request $request)
    {
        $parentAccount = $request->get('parent_ac');
        $bankAccount = Account::query()->findOrFail($parentAccount);
        $totalAccounts = Account::query()->where('parent_ac', $parentAccount)->count() + 1;
        $code = rtrim($bankAccount->code, 0) . str_pad($totalAccounts, 3, 0, STR_PAD_LEFT);
        $code = str_pad($code, 13, 0, STR_PAD_RIGHT);
        $account = (new Account())->fill($request->merge([
            'type_id' => $bankAccount->type_id,
            'code' => $code,
            'is_editable' => 1,
            'is_transactional' => 1,
            'is_active' => 1,
        ])->all());
        $account->save();

        return $account->id;
    }

    /**
     * @param Request $request
     * @return void
     */
    public static function update(Request $request)
    {
        $account = Account::query()->findOrFail($request->get('account_id'));
        $account->name = $request->get('name');
        $account->save();
    }
}
