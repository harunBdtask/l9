<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking\Decorator;

interface BookingFormatterComponentInterface
{
    public function setDetails($bookingDetails = []);

    public function decorate(): array;
}
