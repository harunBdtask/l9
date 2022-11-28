<?php

namespace SkylarkSoft\GoRMG\Knitting\Services\BookingData;

use SkylarkSoft\GoRMG\Knitting\Services\BookingData\BookingDataAdapter;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;

class MainFabricBookingFormatter implements BookingDataFormatter
{

    public function doFormat(BookingDataAdapter $bookingDataAdapter)
    {
        return FabricBooking::query()->where('unique_id', $bookingDataAdapter->getBookingNo())->with('detailsBreakdown')->first();
    }
}