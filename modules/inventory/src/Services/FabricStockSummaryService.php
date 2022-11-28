<?php

namespace SkylarkSoft\GoRMG\Inventory\Services;

use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricStockSummary;

class FabricStockSummaryService
{
    public function summary($detail)
    {
        return FabricStockSummary::query()->where([
            'batch_no' => $detail['batch_no'],
            'style_id' => $detail['style_id'],
            'body_part_id' => $detail['body_part_id'],
            'color_type_id' => $detail['color_type_id'],
            'gmts_item_id' => $detail['gmts_item_id'],
            'color_id' => $detail['color_id'],
            'construction' => $detail['construction'],
            'uom_id' => $detail['uom_id'],
            'fabric_composition_id' => $detail['fabric_composition_id'],
            'dia' => $detail['dia'],
            'ac_dia' => $detail['ac_dia'],
            'gsm' => $detail['gsm'],
            'ac_gsm' => $detail['ac_gsm'],
            'store_id' => $detail['store_id'],
        ])->first();
    }

    public function saveSummary($detail): FabricStockSummary
    {
        $summary = new FabricStockSummary([
            'batch_no' => $detail['batch_no'],
            'style_id' => $detail['style_id'],
            'body_part_id' => $detail['body_part_id'],
            'color_type_id' => $detail['color_type_id'],
            'gmts_item_id' => $detail['gmts_item_id'],
            'color_id' => $detail['color_id'],
            'construction' => $detail['construction'],
            'uom_id' => $detail['uom_id'],
            'fabric_composition_id' => $detail['fabric_composition_id'],
            'dia' => $detail['dia'],
            'ac_dia' => $detail['ac_dia'],
            'gsm' => $detail['gsm'],
            'ac_gsm' => $detail['ac_gsm'],
            'store_id' => $detail['store_id'],
            'receive_qty' => $detail['receive_qty'],
            'balance' => $detail['balance'],
            'balance_amount' => $detail['balance_amount'],
            'receive_amount' => $detail['receive_amount'],
            'fabric_description' => $detail['fabric_description'],
        ]);

        $summary->save();

        return $summary;
    }
}
