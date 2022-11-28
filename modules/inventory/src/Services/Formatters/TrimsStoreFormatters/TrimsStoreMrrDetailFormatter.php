<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\Formatters\TrimsStoreFormatters;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrr;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrrDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceiveDetail;

class TrimsStoreMrrDetailFormatter
{
    public function format(TrimsStoreMrrDetail $detail): array
    {
        return [
            'id' => $detail->id,
            'factory_id' => $detail->factory_id,
            'item_id' => $detail->item_id,
            'store_id' => $detail->store_id,
            'uom_id' => $detail->uom_id,
            'item_name' => $detail->itemGroup->item_group ?? '',
            'item_description' => $detail->item_description,
            'color' => $detail->color->name ?? '',
            'size' => $detail->size,
            'uom_value' => $detail->uom->unit_of_measurement ?? '',
            'planned_garments_qty' => $detail->planned_garments_qty,
            'approval_shade_code' => $detail->approval_shade_code,
            'actual_consumption' => $detail->actual_consumption,
            'total_consumption' => $detail->total_consumption,
            'actual_qty' => $detail->actual_qty,
            'total_delivered_qty' => $detail->total_delivered_qty,
            'rate' => $detail->rate,
            'amount' => $detail->amount,
            'remarks' => $detail->remarks,
        ];
    }
}
