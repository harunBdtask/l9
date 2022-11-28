<?php

namespace SkylarkSoft\GoRMG\Finance\Services\AccountCode;

use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Models\AccountInfo;

class GroupCodeGenerate implements AccountCodeContracts
{
    public function handle(AccountCodeStrategy $strategy): string
    {
        $parentWiseAccount = AccountInfo::query()
            ->where('parent_account_id', $strategy->getParentId())
            ->get()
            ->pluck('accounts_id');

        $totalGroupAccount = Account::query()->where([
            'type_id' => $strategy->getType(),
            'account_type' => Account::GROUP
        ])->whereIn('id', $parentWiseAccount)->count();

        $parentAccountCode = Account::query()
            ->where('id', $strategy->getParentId())
            ->pluck('code')->first();
        $totalGroupAccount = str_pad(++$totalGroupAccount, '2', '0', STR_PAD_LEFT);
        $codeGenerate = $parentAccountCode . $totalGroupAccount;
        return str_pad($codeGenerate, '4', '0', STR_PAD_LEFT);
    }
}
