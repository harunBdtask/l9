<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\States\TrimsItemState;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;

class ColorAndSizeFormatter implements ItemFormatterContract
{
    public function format(TrimsBookingDetails $detail): Collection
    {
        return collect($detail['details'])->map(function ($itemDetail) use ($detail) {
            return [
                'trims_booking_id' => $detail['booking_id'],
                'trims_booking_detail_id' => $detail['id'],
                'po_no' => $detail['po_no'],
                'sensitivity' => $detail['sensitivity'],
                'item_id' => $detail['item_id'],
                'item_name' => $detail['item_name'],
                'item_description' => $detail['item_description'],
                'receive_date' => Carbon::now()->format('Y-m-d'),
                'color_id' => $itemDetail['color_id'],
                'color' => $itemDetail['color'],
                'size_id' => $itemDetail['size_id'],
                'size' => $itemDetail['size'],
                'wo_qty' => $itemDetail['wo_qty'],
                'budget_qty' => $itemDetail['budget_qty'],
                'uom_id' => $detail['cons_uom_id'],
                'uom_value' => $detail['cons_uom_value'],
                'approval_shade_code' => $itemDetail['item_code'],
                'delivery_swatch' => null,
                'is_color' => null,
                'planned_garments_qty' => $itemDetail['pcs'],
                'booking_qty' => $itemDetail['wo_total_qty'],
                'receive_qty' => null,
                'excess_qty' => $itemDetail['excess_percent'],
                'reject_qty' => null,
                'rate' => $itemDetail['rate'],
                'is_qty' => null,
                'quality' => null,
                'dimensions' => null,
                'cf_to_wah' => null,
                'inventory_by' => null,
                'remarks' => null,
            ];
        });
    }
}
