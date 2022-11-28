<?php

namespace SkylarkSoft\GoRMG\Finance\Services\AccountCode;

use SkylarkSoft\GoRMG\Finance\Models\Account;

class ParentCodeGenerate implements AccountCodeContracts
{

    public function handle(AccountCodeStrategy $strategy): string
    {
        $totalAccount = Account::query()->parentAccounts($strategy->getType())->count();
        return $strategy->getType() . ++$totalAccount;
    }
}
