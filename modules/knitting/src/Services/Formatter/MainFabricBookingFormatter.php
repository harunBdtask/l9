<?php

namespace SkylarkSoft\GoRMG\Knitting\Services\Formatter;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;

class MainFabricBookingFormatter implements RequisitionFormatter
{

    public function doFormat(RequisitionFormatAdapter $requisitionFormatAdapter): array
    {
        $bookingDetails = FabricBooking::query()->where('id', $requisitionFormatAdapter->getBookingId())->with('buyer')->first();
        return $bookingDetails->toArray();
    }
}
