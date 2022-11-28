<?php

namespace SkylarkSoft\GoRMG\Inventory\Observers;

use App\Exceptions\DivisionByZeroException;
use Exception;
use SkylarkSoft\GoRMG\Inventory\Exceptions\SummaryNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Services\FabricDateWiseStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStockSummaryService;

class FabricIssueDetailObserver
{
    private $avgRate;

    /**
     * @throws Exception
     */
    public function saved(FabricIssueDetail $detail)
    {
        $summaryService = new FabricStockSummaryService();

        $id = $detail->getOriginal('id');

        $summary = $summaryService->summary($detail);

        if (!$summary) {
            throw new SummaryNotFoundException('No Receive Available for this Item!');
        }

        $rate = $this->getRate($summary);
        $this->avgRate = $rate;

        if (!$id) {
            $newIssueQty = $detail->issue_qty;
            $balance = $summary->balance - $newIssueQty;

            $receiveQty = $summary->receive_qty;
            $receiveAmount = $summary->receive_amount;

            if ($receiveQty == 0) {
                throw new DivisionByZeroException();
            }

            $rate = $receiveAmount / $receiveQty;
            $this->avgRate = $rate;

            $summary->update([
                'issue_qty' => $summary->issue_qty + $newIssueQty,
                'balance' => $balance,
                'balance_amount' => $balance * $rate
            ]);

            $this->updateDateWiseSummary($detail);

            return;
        }

        $previousIssueQty = $summary->issue_qty;
        $issueQtyBeforeEdit = $detail->getOriginal('issue_qty');
        $newIssueQty = $detail->issue_qty;

        $summaryIssueQty = $previousIssueQty - $issueQtyBeforeEdit + $newIssueQty;
        $balance = ($summary->receive_qty + $summary->issue_return_qty) - ($summaryIssueQty + $summary->receive_return_qty);

        $summary->update([
            'issue_qty' => $summaryIssueQty,
            'balance' => $balance,
            'balance_amount' => $balance * $rate
        ]);

        $this->updateDateWiseSummary($detail);


    }

    /**
     * @throws Exception
     */
    private function getRate($summary)
    {
        $receiveQty = $summary->receive_qty;
        $receiveAmount = $summary->receive_amount;

        if ($receiveQty == 0) {
            throw new DivisionByZeroException();
        }

        return $receiveAmount / $receiveQty;
    }

    /**
     * @throws Exception
     */
    public function updateDateWiseSummary(FabricIssueDetail $detail)
    {
        $issueDate = $detail->issue->issue_date;

        $dateWiseSummary = (new FabricDateWiseStockSummaryService())
            ->getDateWiseSummary($detail, $issueDate);

        if (!$dateWiseSummary) {
            (new FabricDateWiseStockSummary([
                'date' => $issueDate,
                'batch_no' => $detail->batch_no,
                'style_id' => $detail->style_id,
                'body_part_id' => $detail->body_part_id,
                'color_type_id' => $detail->color_type_id,
                'gmts_item_id' => $detail->gmts_item_id,
                'color_id' => $detail->color_id,
                'construction' => $detail->construction,
                'uom_id' => $detail->uom_id,
                'fabric_composition_id' => $detail->fabric_composition_id,
                'dia' => $detail->dia,
                'ac_dia' => $detail->ac_dia,
                'gsm' => $detail->gsm,
                'ac_gsm' => $detail->ac_gsm,
                'store_id' => $detail->store_id,
                'issue_qty' => $detail->issue_qty,
                'rate' => $this->avgRate
            ]))->save();

            return;
        }

        $dateWiseIssueQty = $dateWiseSummary->issue_qty - $detail->getOriginal('issue_qty') + $detail->issue_qty;

        $dateWiseSummary->update([
            'issue_qty' => $dateWiseIssueQty,
            'rate' => $this->avgRate
        ]);

    }

    public function deleted(FabricIssueDetail $detail)
    {
        $summary = (new FabricStockSummaryService())->summary($detail);

        $issueQty = $summary->issue_qty - $detail->issue_qty;
        $balanceQty = $summary->balance + $detail->issue_qty;
        $deletedAmount = $detail->issue_qty * $detail->rate;

        $summary->update([
            'issue_qty' => $issueQty,
            'balance' => $balanceQty,
            'balance_amount' => $summary->balance_amount + $deletedAmount,
        ]);
    }
}
