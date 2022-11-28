<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBookingDetails;

class FabricBookingMaxReceiveQty implements MaxReceiveQtyInterface
{

    public function maxReceiveQty($item)
    {
        $bookingId = request('receivable_id');

        $styleName = Order::query()->findOrFail($item['style_id'])->style_name;

        return FabricBookingDetailsBreakdown::query()->where([
            'booking_id' => $bookingId,
            'garments_item_id' => $item['gmts_item_id'],
            'body_part_id' => $item['body_part_id'],
            'color_type_id' => $item['color_type_id'],
            'construction' => $item['construction'],
            'uom' => $item['uom_id'],
            'fabric_composition_id' => $item['fabric_composition_id'],
            'dia_type' => $item['dia_type'],
            'dia' => $item['dia'],
            'color_id' => $item['color_id'],
            'style_name' => $styleName
        ])->sum('actual_wo_qty');
    }
}
