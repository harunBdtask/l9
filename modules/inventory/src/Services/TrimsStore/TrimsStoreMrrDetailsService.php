<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\TrimsStore;

use Carbon\Carbon;

class TrimsStoreMrrDetailsService
{
    public static function formatForBinCardDetails($trimsBinCardDetails)
    {
        return $trimsBinCardDetails->map(function ($detail) {
            return [
                'trims_store_mrr_id' => $detail->trims_store_mrr_id,
                'trims_store_mrr_detail_id' => $detail->id,
                'bin_card_date' => Carbon::now()->format('Y-m-d'),
                'factory_id' => $detail->factory_id,
                'store_id' => $detail->store_id,
                'item_id' => $detail->item_id,
                'item_description' => $detail->item_description,
                'color_id' => $detail->color_id,
                'size_id' => $detail->size_id,
                'size' => $detail->size,
                'uom_id' => $detail->uom_id,
                'approval_shade_code' => $detail->approval_shade_code,
                'booking_qty' => $detail->trimsStoreReceiveDetail->booking_qty,
                'floor_id' => null,
                'room_id' => null,
                'rack_id' => null,
                'shelf_id' => null,
                'bin_id' => null,
                'issue_to' => null,
                'issue_qty' => null,
                'issue_date' => null,
                'remarks' => null,
                'planned_garments_qty' => $detail->planned_garments_qty
            ];
        })->toArray();
    }
}
