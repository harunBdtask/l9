<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\ShortTrimsBookings;

use App\Http\Controllers\Controller;

class TrimsShortBookingController extends Controller
{
    public function mainPage()
    {
        return view('merchandising::booking.short-trims-booking');
    }
}
