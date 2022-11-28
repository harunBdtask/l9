<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Sample;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleBooking;

interface SampleBookingService
{
    public function fetchList(): LengthAwarePaginator;

    public function saveBooking(Request $request): SampleBooking;

    public function updateBooking(Request $request): SampleBooking;
}