<?php

namespace SkylarkSoft\GoRMG\Finance\Services;

class VoucherService
{
    public static function getTypeList(): array
    {
        return [
            [
                'id' => 'debit',
                'type_id' => 1,
                'name' => 'Debit/ Payment Voucher',
            ],
            [
                'id' => 'credit',
                'type_id' => 2,
                'name' => 'Credit/ Received Voucher',
            ],
            [
                'id' => 'journal',
                'type_id' => 3,
                'name' => 'Journal Voucher',
            ]
        ];
    }

    public static function getPayModeList(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Cash',
            ],
            [
                'id' => 2,
                'name' => 'Cheque',
            ],
            [
                'id' => 3,
                'name' => 'LC',
            ]
        ];
    }
}