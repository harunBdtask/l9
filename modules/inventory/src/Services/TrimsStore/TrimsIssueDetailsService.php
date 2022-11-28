<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\TrimsStore;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventoryDetail;

class TrimsIssueDetailsService extends Controller
{
    public static function formatForBinCardDetails($trimsBinCardDetails)
    {
        return $trimsBinCardDetails->map(function ($detail) {
            return [
                'trims_store_bin_card_id' => $detail->trims_store_bin_card_id,
                'trims_store_bin_card_detail_id' => $detail->id,
                'trims_store_mrr_detail_id' => $detail->trims_store_mrr_detail_id,
                'factory_id' => $detail->factory_id,
                'store_id' => $detail->store_id,
                'item_id' => $detail->item_id,
                'uom_id' => $detail->uom_id,
                'color_id' => $detail->color_id,
                'item_description' => $detail->item_description,
                'current_date' => date('Y-m-d'),
                'size_id' => $detail->size_id,
                'size' => $detail->size,
                'approval_shade_code' => $detail->approval_shade_code,
                'issue_qty' => null,
                'issue_date' => $detail->issue_date,
                'mrr_qty' => ($detail->mrrDetail->total_delivered_qty ?? 0) - $detail->issue_qty,
                'floor_id' => $detail->floor_id,
                'room_id' => $detail->room_id,
                'rack_id' => $detail->rack_id,
                'shelf_id' => $detail->shelf_id,
                'bin_id' => $detail->bin_id,
                'planned_garments_qty' => $detail->planned_garments_qty
            ];
        })->toArray();
    }
}
