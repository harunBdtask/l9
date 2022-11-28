<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking\Decorator;

abstract class FormatterDecorator
{
    public $bookingFormatterComponentInterface;

    public function __construct(BookingFormatterComponentInterface $bookingFormatterComponent)
    {
        $this->bookingFormatterComponentInterface = $bookingFormatterComponent;
    }

    abstract public function decorate(): array;
}
