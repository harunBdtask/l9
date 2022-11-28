<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Rules\V3;

use SkylarkSoft\GoRMG\TrimsStore\Services\V3\StockSummaryService;

class QtyRuleCriteria
{
    public function getCriteria(): array
    {
        return [
            'factory_id' => request()->input('factory_id'),
            'buyer_id' => request()->input('buyer_id'),
            'style_id' => request()->input('style_id'),
            'garments_item_id' => request()->input('garments_item_id'),
            'item_id' => request()->input('item_id'),
            'sensitivity_id' => request()->input('sensitivity_id'),
            'supplier_id' => request()->input('supplier_id'),
            'color_id' => request()->input('color_id'),
            'size_id' => request()->input('size_id'),
            'uom_id' => request()->input('uom_id'),
            'floor_id' => request()->input('floor_id'),
            'room_id' => request()->input('room_id'),
            'rack_id' => request()->input('rack_id'),
            'shelf_id' => request()->input('shelf_id'),
            'bin_id' => request()->input('bin_id'),
        ];
    }

    public function getStockSummary()
    {
        return StockSummaryService::setCriteria($this->getCriteria())->getStockSummary();
    }
}
