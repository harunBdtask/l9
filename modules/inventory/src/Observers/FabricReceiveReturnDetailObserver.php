<?php

namespace SkylarkSoft\GoRMG\Inventory\Observers;

use Exception;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Exceptions\SummaryNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Services\FabricDateWiseStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricDateWiseStockSummary;

class FabricReceiveReturnDetailObserver
{
    /**
     * @throws Exception
     */
    public function saved(FabricReceiveReturnDetail $detail)
    {
        $summaryService = new FabricStockSummaryService;

        $id = $detail->getOriginal('id');

        if ($id) {
            return;
        }

        $summary = $summaryService->summary($detail);

        if (!$summary) {
            throw new SummaryNotFoundException('No Receive Available for this Item!');
        }

        $rate = $summary->receive_amount / $summary->receive_qty;

        $balance = $summary->balance - $detail->return_qty;

        $summary->update([
            'receive_return_qty' => $summary->receive_return_qty + $detail->return_qty,
            'balance' => $balance,
            'balance_amount' => $balance * $rate
        ]);

        $returnDate = $detail->receiveReturn->return_date;

        $dateWiseSummary = (new FabricDateWiseStockSummaryService)
            ->getDateWiseSummary($detail, $returnDate);

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
                'receive_return_qty' => $detail->return_qty,
                'date' => $returnDate,
                'rate' => $rate
            ]))->save();

            return;
        }

        $totalReturnQty = $dateWiseSummary->receive_return_qty + $detail->return_qty;

        $dateWiseSummary->update([
            'receive_return_qty' => $totalReturnQty,
            'rate' => $rate
        ]);
    }
}
