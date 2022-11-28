<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBookingDetails;

class ShortFabricBookingMaxReceiveQty implements MaxReceiveQtyInterface
{

    public function maxReceiveQty($item)
    {

        $bookingId = request('receivable_id');

        $styleName = Order::findOrFail($item['style_id'])->style_name;

        $bookings = ShortFabricBookingDetails::query()->where([
            'booking_id'            => $bookingId,
            'item_id'               => $item['gmts_item_id'],
            'body_part_id'          => $item['body_part_id'],
            'color_type_id'         => $item['color_type_id'],
            'construction'          => $item['construction'],
            'uom'                   => $item['uom_id'],
            'fabric_composition_id' => $item['fabric_composition_id'],
            'dia_type'              => $item['dia_type'],
            'style_name'            => $styleName
        ])
            ->whereNotNull('breakdown')
            ->pluck('breakdown')
            ->flatten(1);

        return collect($bookings)
            ->where('dia', $item['dia'])
            ->where('color_id', $item['color_id'])
            ->sum('qty');
    }
}