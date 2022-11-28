<?php


namespace SkylarkSoft\GoRMG\Inventory\Observers;


use SkylarkSoft\GoRMG\Inventory\Exceptions\SummaryNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Models\DateWiseStockSummery;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssueReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsDateWiseStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsStockSummeryService;

class TrimsIssueReturnDetailObserver
{

    /**
     * @throws \Exception
     */
    public function saved(TrimsIssueReturnDetail $detail)
    {

        $styleName = $detail->style_name;
        $itemId = $detail->item_id;
        $uomId = $detail->uom_id;

        $id = $detail->getOriginal('id');

        if ( $id ) {
            return;
        }

        $summary = (new TrimsStockSummeryService)->summary($styleName, $itemId, $uomId);

        if ( !$summary ) {
            throw new SummaryNotFoundException('No Receive Available for this Item!');
        }

        $rate = $summary->receive_amount / $summary->receive_qty;

        $returnQty = $detail->return_qty;

        $balance = $summary->balance - $returnQty;

        $summary->update([
            'issue_return_qty' => $summary->issue_return_qty + $returnQty,
            'balance'            => $balance,
            'balance_amount'     => $balance * $rate
        ]);

        $issueReturnDate = $detail->issueReturn->return_date;

        $dateWiseStockSummary = (new TrimsDateWiseStockSummaryService)
            ->getDateWiseSummary($detail, $issueReturnDate);

        if ( !$dateWiseStockSummary ) {
            (new TrimsDateWiseStockSummary([
                'style_name'       => $detail->style_name,
                'item_id'          => $detail->item_id,
                'uom_id'           => $detail->uom_id,
                'date'             => $issueReturnDate,
                'issue_return_qty' => $detail->return_qty,
                'rate'             => $rate,
                'meta'             => [
                    'item_description' => $detail->item_description
                ]
            ]))->save();
            return;
        }

        $dateWiseStockSummary->update([
            'issue_return_qty' => $dateWiseStockSummary->issue_return_qty + $detail->return_qty,
            'rate' => $rate
        ]);
    }

}
