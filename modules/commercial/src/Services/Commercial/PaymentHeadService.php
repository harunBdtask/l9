<?php

namespace SkylarkSoft\GoRMG\Commercial\Services\Commercial;

class PaymentHeadService
{
    public static function data(): array
    {
        return [
            [
                'id' => 1,
                'text' => 'IFDBC Liability',
            ],
            [
                'id' => 2,
                'text' => 'Bank Charge',
            ],
            [
                'id' => 3,
                'text' => 'Interest',
            ],
        ];
    }
}
