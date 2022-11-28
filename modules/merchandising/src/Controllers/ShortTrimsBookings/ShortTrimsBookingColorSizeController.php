<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\ShortTrimsBookings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\ItemFormatter;

class ShortTrimsBookingColorSizeController extends Controller
{
    public function __invoke(Request $request, ItemFormatter $formatter): \Illuminate\Http\JsonResponse
    {
        $item = ShortTrimsBookingDetails::where($request->except('sensitivity'))->firstOrFail();

        if ($this->wantsStoredDetails($item)) {
            return response()->json($item->details);
        }

        $breakdowns = $formatter->format($item, 'short_trims_booking');

        return response()->json($breakdowns);
    }

    private function wantsStoredDetails($item): bool
    {
        return $item->sensitivity == request('sensitivity') && $item->details;
    }
}
