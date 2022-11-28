<?php

namespace SkylarkSoft\GoRMG\Inventory\Observers;

use SkylarkSoft\GoRMG\Inventory\Exceptions\SummaryNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Services\FabricDateWiseStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStockSummaryService;

class FabricIssueReturnDetailObserver
{
    /**
     * @throws \Exception
     */
    public function saved(FabricIssueReturnDetail $detail)
    {
        $id = $detail->getOriginal('id');

        if ($id) {
            return;
        }

        $summary = (new FabricStockSummaryService())->summary($detail);

        if (!$summary) {
            throw new SummaryNotFoundException('No Receive Available for this Item!');
        }

        $rate = $summary->receive_amount / $summary->receive_qty;
        $returnQty = $detail->return_qty;
        $balance = $summary->balance + $returnQty;

        $summary->update([
            'issue_return_qty' => $summary->issue_return_qty + $returnQty,
            'balance' => $balance,
            'balance_amount' => $balance * $rate
        ]);

        $issueReturnDate = $detail->issueReturn->return_date;

        $dateWiseSummary = (new FabricDateWiseStockSummaryService)->getDateWiseSummary($detail, $issueReturnDate);

        if (!$dateWiseSummary) {
            (new FabricDateWiseStockSummary([
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
                'issue_return_qty' => $detail->return_qty,
                'date' => $rate
            ]))->save();

            return;
        }

        $dateWiseSummary->update([
            'issue_return_qty' => $dateWiseSummary->issue_return_qty + $detail->return_qty,
            'rate' => $rate
        ]);

    }
}
