<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking\Decorator;

class TrimsFormatter implements BookingFormatterComponentInterface
{
    public $bookingDetails;

    public function setDetails($bookingDetails = [])
    {
        $this->bookingDetails = $bookingDetails;
    }

    public function decorate(): array
    {
        return collect($this->bookingDetails)->toArray();
    }
}
