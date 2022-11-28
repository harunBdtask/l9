<?php

namespace SkylarkSoft\GoRMG\Inventory\Observers;

use Exception;
use SkylarkSoft\GoRMG\Inventory\Services\StockSummaryCalculator;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Services\FabricDateWiseStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricDateWiseStockSummary;

class FabricReceiveDetailObserver
{
    private $avgRate;

    /**
     * @throws Exception
     */
    public function saved(FabricReceiveDetail $detail)
    {
        $detailId = $detail->getOriginal('id');

        $receiveQtyBeforeEdit = $detail->getOriginal('receive_qty');
        $amountBeforeEdit = $detail->getOriginal('amount');

        $summaryService = new FabricStockSummaryService();

        $summary = $summaryService->summary($detail);

        if (!$summary) {
            $this->avgRate = $detail->rate;
            $balanceAmount = $detail->receive_qty * $detail->rate;
            $summaryService->saveSummary(array_merge(
                $detail->toArray(),
                [
                    'balance' => $detail->receive_qty,
                    'balance_amount' => $balanceAmount,
                    'receive_amount' => $balanceAmount,
                ]
            ));
            $this->updateDailyStockSummary($detail);

            return;
        }

        if (!$detailId) {

            $summaryCalculator = new StockSummaryCalculator($summary, $detail);

            $this->avgRate = $summaryCalculator->avgRate();

            $summary->update([
                'receive_qty' => $summaryCalculator->totalReceiveQty(),
                'receive_amount' => $summaryCalculator->receiveAmount(),
                'balance' => $summaryCalculator->balance(),
                'balance_amount' => $summaryCalculator->balanceAmount()
            ]);

            $this->updateDailyStockSummary($detail);

            return;
        }

        $totalReceiveQty = $summary->receive_qty - ($receiveQtyBeforeEdit - $detail->receive_qty);
        $balance = $summary->balance - ($receiveQtyBeforeEdit - $detail->receive_qty);
        $receiveAmount = $summary->receive_amount - $amountBeforeEdit + $detail->amount;
        $this->avgRate = $receiveAmount / $totalReceiveQty;
        $balanceAmount = $balance * $this->avgRate;

        $summary->update([
            'receive_qty' => $totalReceiveQty,
            'receive_amount' => $receiveAmount,
            'balance' => $balance,
            'balance_amount' => $balanceAmount
        ]);

        $this->updateDailyStockSummary($detail);
    }

    /**
     * @throws Exception
     */
    private function updateDailyStockSummary(FabricReceiveDetail $detail)
    {
        $dateWiseService = new FabricDateWiseStockSummaryService();

        $date = $detail->receive->receive_date;

        $dateWiseSummary = $dateWiseService
            ->getDateWiseSummary($detail, $date);

        if (!$dateWiseSummary) {
            (new FabricDateWiseStockSummary([
                'date' => $date,
                'batch_no' => $detail->batch_no,
                'style_id' => $detail->style_id,
                'body_part_id' => $detail->body_part_id,
                'color_type_id' => $detail->color_type_id,
                'gmts_item_id' => $detail->gmts_item_id,
                'color_id' => $detail->color_id,
                'construction' => $detail->construction,
                'uom_id' => $detail->uom_id,
                'fabric_composition_id' => $detail->fabric_composition_id,
                'fabric_description' => $detail->fabric_description,
                'dia' => $detail->dia,
                'ac_dia' => $detail->ac_dia,
                'gsm' => $detail->gsm,
                'ac_gsm' => $detail->ac_gsm,
                'store_id' => $detail->store_id,
                'receive_qty' => $detail->receive_qty,
                'rate' => $detail->rate
            ]))->save();

            return;
        }

        $receiveQty = $dateWiseSummary->receive_qty - $detail->getOriginal('receive_qty') + $detail->receive_qty;

        $dateWiseSummary->update([
            'receive_qty' => $receiveQty,
            'rate' => $this->avgRate
        ]);
    }

    public function deleted(FabricReceiveDetail $detail)
    {
        $summary = (new FabricStockSummaryService())->summary($detail);

        $receiveQty = $summary->receive_qty - $detail->receive_qty;
        $balanceQty = $summary->balance - $detail->receive_qty;
        $deletedAmount = $detail->receive_qty * $detail->rate;
        $receiveAmount = $summary->receive_amount - $deletedAmount;
        $balanceAmount = $summary->balance_amount - $deletedAmount;

        $summary->update([
            'receive_qty' => $receiveQty,
            'balance' => $balanceQty,
            'receive_amount' => $receiveAmount,
            'balance_amount' => $balanceAmount
        ]);
    }
}
