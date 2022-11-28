<?php

namespace SkylarkSoft\GoRMG\Knitting\Services\Formatter;

use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;

class ShortFabricBookingFormatter implements RequisitionFormatter
{

    public function doFormat(RequisitionFormatAdapter $requisitionFormatAdapter): array
    {
        $bookingDetails = ShortFabricBooking::query()->where('id', $requisitionFormatAdapter->getBookingId())->with('buyer')->first();
        return $bookingDetails->toArray();
    }
}
