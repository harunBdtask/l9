<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking;

class TrimsBookingService
{
    /**
     * @param $colorId integer
     * @param $sizeId integer | array
     * @param $itemId
     * @param $budgetId
     * @param $bookingId
     */
    public function calculateBalance($colorId, $sizeId, $itemId, $budgetId, $bookingId)
    {
        if (is_array($sizeId)) {
        }
    }
}
