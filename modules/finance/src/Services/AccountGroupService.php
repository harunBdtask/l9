<?php

namespace SkylarkSoft\GoRMG\Finance\Services;

class AccountGroupService
{
    public static function groups(): array
    {
        return [
            [
                'id' => 'account_type',
                'text' => 'Account Type'
            ],
            [
                'id' => 'parent_account',
                'text' => 'Parent Account'
            ],
            [
                'id' => 'group_account',
                'text' => 'Group Account'
            ],
            [
                'id' => 'control_account',
                'text' => 'Control Account'
            ],
            [
                'id' => 'ledger_account',
                'text' => 'Ledger Account'
            ],
        ];
    }
}
