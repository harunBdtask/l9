<?php

namespace SkylarkSoft\GoRMG\Finance\Services\AccountCode;

use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Models\AccountInfo;

class ControlCodeGenerate implements AccountCodeContracts
{
    public function handle(AccountCodeStrategy $strategy): string
    {
        $parentWiseAccount = AccountInfo::query()
            ->where('parent_account_id', $strategy->getParentId())
            ->where('group_account_id', $strategy->getGroupId())
            ->get()
            ->pluck('accounts_id');

        $totalControlAccount = Account::query()->where([
            'type_id' => $strategy->getType(),
            'account_type' => Account::CONTROL
        ])->whereIn('id', $parentWiseAccount)->count();

        $parentAccountCode = Account::query()
            ->where('id', $strategy->getGroupId())
            ->pluck('code')->first();
        $totalControlAccount = str_pad(++$totalControlAccount, '2', '0', STR_PAD_LEFT);
        $codeGenerate = $parentAccountCode . $totalControlAccount;

        return str_pad($codeGenerate, '6', '0', STR_PAD_LEFT);
    }
}
