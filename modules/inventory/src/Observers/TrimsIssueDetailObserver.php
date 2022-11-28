<?php

namespace SkylarkSoft\GoRMG\Inventory\Observers;

use App\Exceptions\DivisionByZeroException;
use Exception;
use SkylarkSoft\GoRMG\Inventory\Exceptions\SummaryNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsDateWiseStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsStockSummeryService;

class TrimsIssueDetailObserver
{

    private $avgRate;

    /**
     * @throws Exception
     */
    public function saved(TrimsIssueDetail $detail)
    {
        $summaryService = new TrimsStockSummeryService;
        $styleName = $detail->style_name;
        $itemId = $detail->item_id;
        $uomId = $detail->uom_id;

        $editing = $detail->getOriginal('id');

        $summary = $summaryService->summary($styleName, $itemId, $uomId);

        if (!$summary) {
            throw new SummaryNotFoundException('No Receive Available for this Item!');
        }

        $rate = $this->getRate($summary);
        $this->avgRate = $rate;

        if (!$editing) {

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
    private function updateDateWiseSummary(TrimsIssueDetail $detail)
    {

        $issueDate = $detail->trimsIssue->issue_date;

        $dateWiseStockSummary = (new TrimsDateWiseStockSummaryService)
            ->getDateWiseSummary($detail, $issueDate);

        if (!$dateWiseStockSummary) {
            (new TrimsDateWiseStockSummary([
                'style_name' => $detail->style_name,
                'item_id' => $detail->item_id,
                'uom_id' => $detail->uom_id,
                'issueDate' => $issueDate,
                'issue_qty' => $detail->issue_qty,
                'rate' => $this->avgRate,
                'meta' => [
                    'item_description' => $detail->item_description,
                ]
            ]))->save();

            return;
        }

        $dateWiseIssueQty = $dateWiseStockSummary->issue_qty - $detail->getOriginal('issue_qty') + $detail->issue_qty;

        $dateWiseStockSummary->update([
            'issue_qty' => $dateWiseIssueQty,
            'rate' => $this->avgRate
        ]);

    }
}
