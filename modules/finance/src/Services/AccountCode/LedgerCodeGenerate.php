<?php

namespace SkylarkSoft\GoRMG\Finance\Services\AccountCode;

use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Models\AccountInfo;

class LedgerCodeGenerate implements AccountCodeContracts
{

    public function handle(AccountCodeStrategy $strategy): string
    {

        $parentWiseAccount = AccountInfo::query()
            ->where('parent_account_id', $strategy->getParentId())
            ->where('group_account_id', $strategy->getGroupId())
            ->where('control_account_id', $strategy->getControlId())
            ->get()
            ->pluck('accounts_id');

        $totalLedgerAccount = Account::query()->where([
            'type_id' => $strategy->getType(),
            'account_type' => Account::LEDGER
        ])->whereIn('id', $parentWiseAccount)->count();

        $parentAccountCode = Account::query()
            ->where('id', $strategy->getControlId())
            ->pluck('code')->first();
        $totalLedgerAccount = str_pad(++$totalLedgerAccount, '3', '0', STR_PAD_LEFT);
        $codeGenerate = $parentAccountCode . $totalLedgerAccount;

        return str_pad($codeGenerate, '9', '0', STR_PAD_LEFT);

    }
}
