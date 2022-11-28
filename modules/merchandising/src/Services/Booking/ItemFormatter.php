<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking;

interface ItemFormatter
{
    public function format($data, $type = null): array;
}
