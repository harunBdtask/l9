<?php

namespace SkylarkSoft\GoRMG\Finance\Services\AccountCode;

interface AccountCodeContracts
{
    const ASSET = 1, LIABILITY = 2, EQUITY = 3, REVENUE_OP = 4, REVENUE_NOP = 5, EXPENSE_OP = 6, EXPENSE_NOP = 7;

    public function handle(AccountCodeStrategy $strategy): string;
}
