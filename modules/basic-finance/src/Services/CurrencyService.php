<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services;

class CurrencyService
{
    public static function currencies(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'BDT',
            ],
            [
                'id' => 2,
                'name' => 'USD',
            ],
            [
                'id' => 3,
                'name' => 'EURO',
            ],
            [
                'id' => 4,
                'name' => 'GBP',
            ],
        ];
    }
}
