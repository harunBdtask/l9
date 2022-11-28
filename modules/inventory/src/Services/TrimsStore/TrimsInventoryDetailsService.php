<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\TrimsStore;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventoryDetail;

class TrimsInventoryDetailsService extends Controller
{
    public static function formatForReceiveDetails($trimsInventoryDetails)
    {
        return $trimsInventoryDetails->map(function ($detail) {
            return [
                'trims_inventory_detail_id' => $detail->id,
                'factory_id' => $detail->factory_id,
                'store_id' => $detail->store_id,
                'current_date' => Carbon::now()->format('Y-m-d'),
                'item_id' => $detail->item_id,
                'item_description' => $detail->item_description,
                'color_id' => $detail->color_id,
                'size_id' => $detail->size_id,
                'size' => $detail->size,
                'planned_garments_qty' => $detail->planned_garments_qty,
                'booking_qty' => $detail->booking_qty,
                'receive_qty' => null,
                'receive_date' => null,
                'receive_return_qty' => null,
                'receive_return_date' => null,
                'excess_qty' => $detail->excess_qty,
                'uom_id' => $detail->uom_id,
                'rate' => $detail->rate,
                'total_receive_amount' => $detail->receive_qty * $detail->rate,
                'remarks' => null,
                'approval_shade_code' => $detail->approval_shade_code
            ];
        })->toArray();
    }
}
