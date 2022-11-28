<?php

namespace SkylarkSoft\GoRMG\Inventory\Services;

class StockSummaryCalculator
{

    private $totalReceiveQty;
    private $avgRate;
    private $receiveAmount;
    public $balance;

    public function __construct($stockSummary, $detail)
    {
        $previousAmount = $stockSummary->receive_amount;
        $newAmount = $detail->rate * $detail->receive_qty;
        $this->totalReceiveQty = $stockSummary->receive_qty + $detail->receive_qty;
        $this->receiveAmount = $stockSummary->receive_amount + ($detail->rate * $detail->receive_qty);

        $avgRate = ($previousAmount + $newAmount) / $this->totalReceiveQty;
        $this->avgRate = $avgRate;

        $this->balance = $stockSummary->balance + $detail->receive_qty;
    }

    public function totalReceiveQty()
    {
        return $this->totalReceiveQty;
    }

    public function avgRate()
    {
        return $this->avgRate;
    }

    public function receiveAmount()
    {
        return $this->receiveAmount;
    }

    public function balance()
    {
        return $this->balance;
    }

    public function balanceAmount()
    {
        return $this->balance * $this->avgRate;
    }
}