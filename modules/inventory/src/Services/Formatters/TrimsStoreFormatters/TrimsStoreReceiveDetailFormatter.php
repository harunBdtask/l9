<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\Formatters\TrimsStoreFormatters;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceiveDetail;

class TrimsStoreReceiveDetailFormatter
{
    public function format(TrimsStoreReceiveDetail $detail): array
    {
        $previousReceiveQty = TrimsStoreReceiveDetail::query()
            ->where('trims_inventory_detail_id', $detail->trims_inventory_detail_id)
            ->sum('receive_qty');

        $currentDate = $detail['current_date'] ? Carbon::make($detail['current_date']) : null;
        $todayDate = Carbon::now();

        return array_merge($detail->toArray(), [
            'item_name' => $detail->itemGroup->item_group,
            'color' => $detail->color->name,
            'uom_value' => $detail->uom->unit_of_measurement,
            'total_receive_qty' => $detail['receive_qty'],
            'ageing' => $detail['current_date'] ? $currentDate->diffInDays($todayDate) : 0,
        ]);
    }
}
