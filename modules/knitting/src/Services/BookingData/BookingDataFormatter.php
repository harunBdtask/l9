<?php

namespace SkylarkSoft\GoRMG\Knitting\Services\BookingData;


interface BookingDataFormatter
{
    public function doFormat(BookingDataAdapter $bookingDataAdapter);
}