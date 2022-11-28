<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Services\Calculations;

class AvailableQtyCalculator
{
    private const IN = 'in';
    private const OUT = 'out';

    /**
     * @param $data
     * @return int|mixed
     */
    public function calculate($data)
    {
        $transactions = collect($data);
        $totalIn = $transactions->where('trn_type', self::IN)->sum('qty') ?: 0;
        $totalOut = $transactions->where('trn_type', self::OUT)->sum('qty') ?: 0;
        return $totalIn - $totalOut;
    }
}
