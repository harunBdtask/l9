<?php

namespace SkylarkSoft\GoRMG\Finance\Services\AccountCode;

use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Models\AccountInfo;

class SubLedgerCodeGenerate implements AccountCodeContracts
{

    public function handle(AccountCodeStrategy $strategy): string
    {

        $parentWiseAccount = AccountInfo::query()
            ->where('parent_account_id', $strategy->getParentId())
            ->where('group_account_id', $strategy->getGroupId())
            ->where('control_account_id', $strategy->getControlId())
            ->where('ledger_account_id', $strategy->getLedgerId())
            ->get()
            ->pluck('accounts_id');

        $totalSubLedgerAccount = Account::query()->where([
            'type_id' => $strategy->getType(),
            'account_type' => Account::SUB_LEDGER
        ])->whereIn('id', $parentWiseAccount)->count();

        $parentAccountCode = Account::query()
            ->where('id', $strategy->getLedgerId())
            ->pluck('code')->first();
        $totalSubLedgerAccount = str_pad(++$totalSubLedgerAccount, '2', '0', STR_PAD_LEFT);
        $codeGenerate = $parentAccountCode . ' (' . $totalSubLedgerAccount . ')';

        return str_pad($codeGenerate, '14', '0', STR_PAD_LEFT);

    }
}
