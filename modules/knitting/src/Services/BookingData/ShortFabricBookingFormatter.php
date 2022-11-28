<?php

namespace SkylarkSoft\GoRMG\Knitting\Services\BookingData;

use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;

class ShortFabricBookingFormatter implements BookingDataFormatter
{

    public function doFormat(BookingDataAdapter $bookingDataAdapter)
    {
        return ShortFabricBooking::query()->where('id', $bookingDataAdapter->getBookingNo())->with('detailsBreakdown')->first();
    }
}