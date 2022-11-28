<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleBookingBeforeOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleBookingConfirmOrder;

class SampleBookingServiceBeforeOrder implements SampleBookingService
{

    public function fetchList(): LengthAwarePaginator
    {
        return SampleBookingBeforeOrder::with('factory:id,group_name', 'buyer:id,name', 'supplier:id,name')
            ->paginate(15);
    }

    public function saveBooking(Request $request): SampleBooking
    {
        $sampleBooking = new SampleBookingBeforeOrder($request->all());
        $sampleBooking->save();
        return $sampleBooking;
    }

    public function updateBooking(Request $request): SampleBooking
    {
        $sampleBooking = SampleBookingBeforeOrder::findOrFail($request->input('id'));
        $sampleBooking->update($request->all());
        return $sampleBooking;
    }
}