<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Month;

class MonthService
{
    public static function months(): array
    {
        return [
            [
                'id' => '01',
                'text' => 'January'
            ],
            [
                'id' => '02',
                'text' => 'February'
            ],
            [
                'id' => '03',
                'text' => 'March'
            ],
            [
                'id' => '04',
                'text' => 'April'
            ],
            [
                'id' => '05',
                'text' => 'May'
            ],
            [
                'id' => '06',
                'text' => 'June'
            ],
            [
                'id' => '07',
                'text' => 'July'
            ],
            [
                'id' => '08',
                'text' => 'August'
            ],
            [
                'id' => '09',
                'text' => 'September'
            ],
            [
                'id' => '10',
                'text' => 'October'
            ],
            [
                'id' => '11',
                'text' => 'November'
            ],
            [
                'id' => '12',
                'text' => 'December'
            ],
        ];
    }

    public static function getMonth ($month)
    {
        return collect(self::months())->firstWhere('id', $month)['text'] ?? null;
    }
}
