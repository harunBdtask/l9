<?php

namespace SkylarkSoft\GoRMG\Inventory\Observers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Exceptions\DateNotAvailableException;
use SkylarkSoft\GoRMG\Inventory\Exceptions\SummaryNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Models\YarnDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\YarnTransferDetail;
use SkylarkSoft\GoRMG\Inventory\Services\YarnDateWiseSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;

class YarnTransferDetailObserver
{
    /**
     * @throws DateNotAvailableException
     */
    public function saved(YarnTransferDetail $yarn)
    {
        $yarnTransfer = $yarn->transfer()->first();

        try {
            /*
             * Update From Stock Summary
             * */
            $fromStoreSummary = (new YarnStockSummaryService())->summary(array_merge(
                $yarn->toArray(),
                ['store_id' => $yarnTransfer->from_store_id]
            ));

            $balance = $fromStoreSummary->balance - $yarn->transfer_qty;
            $issueQty = $fromStoreSummary->issue_qty + $yarn->transfer_qty;
            $fromStoreSummary->update([
                'issue_qty' => $issueQty,
                'balance' => $balance,
                'issue_amount' => $issueQty * $yarn->rate,
                'balance_amount' => $balance * $yarn->rate
            ]);

            $this->updateDateWiseSummary($yarn, $fromStoreSummary);

            /*
             * Update To Stock Summary
             * */
            $toStoreSummary = (new YarnStockSummaryService())->summary(array_merge(
                $yarn->toArray(),
                ['store_id' => $yarnTransfer->to_store_id]
            ));

            $balance = $toStoreSummary->balance + $yarn->transfer_qty;
            $receiveQty = $toStoreSummary->receive_qty + $yarn->transfer_qty;
            $toStoreSummary->update([
                'transferred_from' => $yarnTransfer->from_store_id,
                'transfer_qty' => $toStoreSummary->transfer_qty + $yarn->transfer_qty,
                'receive_qty' => $receiveQty,
                'rate' => $yarn->rate,
                'balance' => $balance,
                'receive_amount' => $receiveQty * $yarn->rate,
                'balance_amount' => $balance * $yarn->rate
            ]);
        } catch (ModelNotFoundException $e) {
            /*
             * Update To Stock Summary if not exist
             * */
            YarnStockSummary::query()->create([
                'yarn_count_id' => $yarn->yarn_count_id,
                'yarn_composition_id' => $yarn->yarn_composition_id,
                'yarn_type_id' => $yarn->yarn_type_id,
                'yarn_color' => $yarn->yarn_color,
                'yarn_lot' => $yarn->yarn_lot,
                'uom_id' => $yarn->uom_id,
                'store_id' => $yarnTransfer->to_store_id,
                'transferred_from' => $yarnTransfer->from_store_id,
                'transfer_qty' => $yarn->transfer_qty,
                'receive_qty' => $yarn->transfer_qty,
                'rate' => $yarn->rate,
                'balance' => $yarn->transfer_qty,
                'receive_amount' => $yarn->transfer_qty * $yarn->rate,
                'balance_amount' => $yarn->transfer_qty * $yarn->rate
            ]);
        }
    }

    /**
     * @throws SummaryNotFoundException
     * @throws DateNotAvailableException
     */
    public function deleted(YarnTransferDetail $yarn)
    {
        $yarnTransfer = $yarn->transfer()->first();

        $fromStoreSummary = (new YarnStockSummaryService())->summary(array_merge(
            $yarn->toArray(),
            ['store_id' => $yarnTransfer->from_store_id]
        ));

        $balance = $fromStoreSummary->balance + $yarn->transfer_qty;
        $issueQty = $fromStoreSummary->issue_qty - $yarn->transfer_qty;

        $fromStoreSummary->update([
            'issue_qty' => $issueQty,
            'balance' => $balance,
            'issue_amount' => $issueQty * $yarn->rate,
            'balance_amount' => $balance * $yarn->rate
        ]);


        /*
         * Update To Stock Summary
         * */
        $toStoreSummary = (new YarnStockSummaryService())->summary(array_merge(
            $yarn->toArray(),
            ['store_id' => $yarnTransfer->to_store_id]
        ));

        if ($toStoreSummary->balance != $toStoreSummary->receive_qty) {
            throw new SummaryNotFoundException('Receive and balance are not same!');
        }

        $balance = $toStoreSummary->balance - $yarn->transfer_qty;
        $receiveQty = $toStoreSummary->receive_qty - $yarn->transfer_qty;
        $toStoreSummary->update([
            'transferred_from' => $yarnTransfer->from_store_id,
            'transfer_qty' => $toStoreSummary->transfer_qty - $yarn->transfer_qty,
            'receive_qty' => $receiveQty,
            'rate' => $yarn->rate,
            'balance' => $balance,
            'receive_amount' => $receiveQty * $yarn->rate,
            'balance_amount' => $balance * $yarn->rate
        ]);


        /*
         * Update From Date wise Stock Summary
         * */
        $dateWiseSummary = (new YarnDateWiseSummaryService())->getDateWiseSummary(array_merge(
            $yarn->toArray(),
            ['store_id' => $yarnTransfer->to_store_id]
        ), $yarnTransfer->transfer_date);

        $dateWiseSummary->update([
            'transfer_qty' => $dateWiseSummary->transfer_qty - $yarn->transfer_qty,
        ]);
    }

    /**
     * @throws DateNotAvailableException
     */
    private function updateDateWiseSummary(YarnTransferDetail $yarn, $summary)
    {
        $yarnTransfer = $yarn->transfer()->first();

        /*
         * Update To Date wise Stock Summary
         * */
        $toStoreSummary = (new YarnDateWiseSummaryService())->getDateWiseSummary(array_merge(
            $yarn->toArray(),
            ['store_id' => $yarnTransfer->to_store_id]
        ), $yarnTransfer->transfer_date);

        if ($toStoreSummary) {
            $toStoreSummary->update([
                'transfer_qty' => $toStoreSummary->transfer_qty + $yarn->transfer_qty
            ]);
        } else {
            YarnDateWiseStockSummary::query()->create([
                'yarn_count_id' => $yarn->yarn_count_id,
                'yarn_composition_id' => $yarn->yarn_composition_id,
                'yarn_type_id' => $yarn->yarn_type_id,
                'yarn_color' => $yarn->yarn_color,
                'yarn_lot' => $yarn->yarn_lot,
                'uom_id' => $yarn->uom_id,
                'date' => $yarnTransfer->transfer_date,
                'store_id' => $yarnTransfer->to_store_id,
                'transferred_from' => $yarnTransfer->from_store_id,
                'transfer_qty' => $yarn->transfer_qty,
                'rate' => $yarn->rate,
            ]);
        }
    }
}
