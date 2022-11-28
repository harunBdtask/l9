<?php

namespace SkylarkSoft\GoRMG\Knitting\Services\BookingData;

use SkylarkSoft\GoRMG\Knitting\Services\BookingData\DefaultFormatter;

class BookingDataAdapter
{
    private $bindings = [
        'main' => MainFabricBookingFormatter::class,
        'short' => ShortFabricBookingFormatter::class,
        'sample' => DefaultFormatter::class,
    ];

    private $salesOrder;

    private function __construct($value)
    {
        $this->salesOrder = $value;
    }

    public static function for($value): BookingDataAdapter
    {
        return new static($value);
    }

    public function getBookingNo()
    {
        return $this->salesOrder->booking_no;
    }

    public function format()
    {
        if (!isset($this->salesOrder->booking_type)) {
            return (new DefaultFormatter)->doFormat($this);
        }
        return (new $this->bindings[$this->salesOrder->booking_type])->doFormat($this);
    }
}
