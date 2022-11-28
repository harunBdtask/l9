<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Actions;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\BasicFinance\Models\BankAccount;

class BankAccountCreate
{
    public function handle($account)
    {
        BankAccount::query()->create([
            'account_id' => $account->id,
            'bank_id' => $account->parentAc->bank->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'account_number' => $account->name,
        ]);
    }
}
