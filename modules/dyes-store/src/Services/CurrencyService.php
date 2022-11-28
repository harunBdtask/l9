<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services;

class CurrencyService
{
    public static function currencyData(): array
    {
        return [
            [
                'id' => 1,
                'text' => 'USD',
            ],
            [
                'id' => 2,
                'text' => 'BDT',
            ],
            [
                'id' => 3,
                'text' => 'GBP',
            ],
            [
                'id' => 4,
                'text' => 'EURO',
            ],
        ];
    }
}
