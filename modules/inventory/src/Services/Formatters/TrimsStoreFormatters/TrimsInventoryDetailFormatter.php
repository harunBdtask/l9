<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\Formatters\TrimsStoreFormatters;

use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventoryDetail;

class TrimsInventoryDetailFormatter
{
    public function format(TrimsInventoryDetail $detail): array
    {
        return array_merge($detail->toArray(), [
            'item_name' => $detail->itemGroup->item_group,
            'color' => $detail->color->name,
            'uom_value' => $detail->uom->unit_of_measurement,
            'inventory_by' => $detail->updatedBy->screen_name,
        ]);
    }
}
