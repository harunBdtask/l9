<?php

namespace SkylarkSoft\GoRMG\Inventory\Services;

use SkylarkSoft\GoRMG\Inventory\Exceptions\DateNotAvailableException;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricDateWiseStockSummary;

class FabricDateWiseStockSummaryService
{
    /**
     * @throws DateNotAvailableException
     */
    public function getDateWiseSummary($detail, $date)
    {
        if (!$date) {
            throw new DateNotAvailableException('Date is not available for date wise summary');
        }

        return FabricDateWiseStockSummary::query()->where([
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
        ])->whereDate('date', $date)->first();
    }

    public function saveNewDateWiseSummary($detail, $date)
    {
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
            'receive_qty' => $detail->receive_qty,
            'date' => $date
        ]))->save();
    }
}
