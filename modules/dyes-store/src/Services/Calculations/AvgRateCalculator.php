<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services\Calculations;

class AvgRateCalculator
{
    /**
     * @param $transactions
     * @return float|int
     */
    public static function calculate($transactions)
    {
        if (!count($transactions)) {
            return 0;
        }
        $totalRate = array_sum(collect($transactions)->pluck('total')->toArray());
        $totalQty = array_sum(collect($transactions)->pluck('qty')->toArray());
        $avgRate = $totalRate / $totalQty;
        return round($avgRate, 2);
    }

}
