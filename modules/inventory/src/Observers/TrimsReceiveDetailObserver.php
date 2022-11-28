<?php

namespace SkylarkSoft\GoRMG\Inventory\Observers;

use SkylarkSoft\GoRMG\Inventory\Models\TrimsDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStockSummery;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsDateWiseStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsStockSummeryService;

class TrimsReceiveDetailObserver
{

    private $avgRate;

    /**
     * @throws \Exception
     */
    public function saved(TrimsReceiveDetail $detail)
    {
        $detailId = $detail->getOriginal('id');

        $receiveQtyBeforeEdit = $detail->getOriginal('receive_qty');
        $amountBeforeEdit = $detail->getOriginal('amount');


        $styleName = $detail->style_name;
        $itemId = $detail->item_id;
        $uomId = $detail->uom_id;

        $summaryService = new TrimsStockSummeryService;

        $summary = $summaryService->summary($styleName, $itemId, $uomId);


        if ( !$summary ) {
            $this->avgRate = $detail->rate;
            $summaryService->addNewSummery($detail);
            $this->updateDailyStockSummary($detail);
            return;
        }

        if ( !$detailId ) {
            $previousAmount = $summary->receive_amount;
            $newAmount = $detail->rate * $detail->receive_qty;
            $totalReceiveQty = $summary->receive_qty + $detail->receive_qty;
            $newReceiveAmount = $summary->receive_amount + ($detail->rate * $detail->receive_qty);

            $this->avgRate = ($previousAmount + $newAmount) / $totalReceiveQty;

            $balance = $summary->balance + $detail->receive_qty;
            $balanceAmount = $balance * $this->avgRate;


            $summary->update([
                'receive_qty'    => $totalReceiveQty,
                'receive_amount' => $newReceiveAmount,
                'balance'        => $balance,
                'balance_amount' => $balanceAmount
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
            'receive_qty'    => $totalReceiveQty,
            'receive_amount' => $receiveAmount,
            'balance'        => $balance,
            'balance_amount' => $balanceAmount
        ]);

        $this->updateDailyStockSummary($detail);
    }

    /**
     * @throws \Exception
     */
    private function updateDailyStockSummary(TrimsReceiveDetail $detail)
    {
        $receive = $detail->trimsReceive()->first();
        $receiveDate = $receive->receive_date;

        $dateWiseService = new TrimsDateWiseStockSummaryService;

        $dateWiseSummary = $dateWiseService
            ->getDateWiseSummary($detail, $receiveDate);

        if ( !$dateWiseSummary ) {
            (new TrimsDateWiseStockSummary([
                'style_name'  => $detail->style_name,
                'item_id'     => $detail->item_id,
                'uom_id'      => $detail->uom_id,
                'date'        => $receiveDate,
                'receive_qty' => $detail->receive_qty,
                'rate'        => $this->avgRate,
                'meta'        => [
                    'item_description' => $detail->item_description,
                    'order_uniq_id'    => $detail->order_uniq_id
                ]
            ]))->save();
            return;
        }

        $receiveQty = $dateWiseSummary->receive_qty - $detail->getOriginal('receive_qty') + $detail->receive_qty;

        $dateWiseSummary->update([
            'receive_qty' => $receiveQty,
            'rate'        => $this->avgRate
        ]);
    }
}
