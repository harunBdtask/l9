<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore;

use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricStockSummary;

class BalanceQty
{
    public function balance($detail)
    {
        return FabricStockSummary::query()
                   ->where('batch_no', $detail->batch_no)
                   ->where('style_id', $detail->style_id)
                   ->where('body_part_id', $detail->body_part_id)
                   ->where('color_id', $detail->color_id)
                   ->where('construction', $detail->construction)
                   ->where('fabric_description', $detail->fabric_description)
                   ->where('uom_id', $detail->uom_id)
                   ->where('fabric_composition_id', $detail->fabric_composition_id)
                   ->where('dia', $detail->dia)
                   ->where('ac_dia', $detail->ac_dia)
                   ->where('gsm', $detail->gsm)
                   ->where('ac_gsm', $detail->ac_gsm)
                   ->where('store_id', $detail->store_id)
                   ->first()['balance'] ?? 0;
    }
}
