<?php


namespace SkylarkSoft\GoRMG\Inventory\Observers;


use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\Inventory\Exceptions\SummaryNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceiveReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsDateWiseStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsStockSummeryService;

class TrimsReceiveReturnDetailObserver
{

    /**
     * @throws \Exception
     */
    public function saved(TrimsReceiveReturnDetail $detail)
    {
        $summaryService = new TrimsStockSummeryService;

        $styleName = $detail->style_name;
        $itemId = $detail->item_id;
        $uomId = $detail->uom_id;

        $id = $detail->getOriginal('id');

        if ( $id ) {
            return;
        }


        $summary = $summaryService->summary($styleName, $itemId, $uomId);

        if ( !$summary ) {
            throw new SummaryNotFoundException('No Receive Available for this Item!');
        }

        $rate = $summary->receive_amount / $summary->receive_qty;

        $balance = $summary->balance - $detail->return_qty;

        $summary->update([
            'receive_return_qty' => $summary->receive_return_qty + $detail->return_qty,
            'balance'          => $balance,
            'balance_amount'   => $balance * $rate
        ]);

        $returnDate = $detail->receiveReturn->return_date;

        $dateWiseSummary = (new TrimsDateWiseStockSummaryService)->getDateWiseSummary(
            $detail,
            $returnDate
        );

        if ( !$dateWiseSummary ) {
            (new TrimsDateWiseStockSummary([
                'style_name'         => $detail->style_name,
                'item_id'            => $detail->item_id,
                'uom_id'             => $detail->uom_id,
                'date'               => $returnDate,
                'receive_return_qty' => $detail->return_qty,
                'rate'               => $rate,
                'meta'               => [
                    'item_description' => $detail->item_description,
                ],
            ]))->save();
            return;
        }


        $totalReturnQty = $dateWiseSummary->receive_return_qty + $detail->return_qty;

        $dateWiseSummary->update([
            'receive_return_qty' => $totalReturnQty,
            'rate'               => $rate
        ]);
    }

}
