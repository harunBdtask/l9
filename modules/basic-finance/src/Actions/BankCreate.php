<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Actions;

use SkylarkSoft\GoRMG\BasicFinance\Models\Bank;

class BankCreate
{
    public function handle($accountId)
    {
        Bank::query()->create([
            'account_id' => $accountId,
        ]);
    }
}
