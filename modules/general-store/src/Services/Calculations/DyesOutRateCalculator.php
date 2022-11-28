<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Services\Calculations;

class DyesOutRateCalculator
{
    const IN = 'in';
    const OUT = 'out';

    public static function calculate($transactions)
    {
        if (!count($transactions)) {
            return 0;
        }

        $inTotal = 0;
        $inQty = 0;
        $outTotal = 0;
        $outQty = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->trn_type == self::IN) {
                $inTotal += $transaction->total;
                $inQty += $transaction->qty;
            } elseif ($transaction->trn_type == self::OUT) {
                $outTotal += $transaction->total;
                $outQty += $transaction->qty;
            }
        }
        $quantity = $inQty - $outQty;

        return $quantity !== 0 ? round(($inTotal - $outTotal) / $quantity, 2) : 0;
    }
}
