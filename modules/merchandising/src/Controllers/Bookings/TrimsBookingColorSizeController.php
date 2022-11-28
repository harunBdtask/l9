<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\Decorator\FormatWithGroupFields;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\Decorator\StockWiseFormat;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\Decorator\TrimsFormatter;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\ItemFormatter;
use SkylarkSoft\GoRMG\Merchandising\Services\FileUploadRemoveService;

class TrimsBookingColorSizeController extends Controller
{
    public function __invoke(Request $request, ItemFormatter $formatter): JsonResponse
    {
        $item = TrimsBookingDetails::where($request->except('sensitivity'))->firstOrFail();

        if ($this->wantsStoredDetails($item)) {
            $breakdown = new TrimsFormatter();
            $breakdown->setDetails($item->details);
            $stockWiseBreakdown = (new StockWiseFormat($breakdown));
            $breakdown->setDetails($stockWiseBreakdown->decorate());
            $formatWithGroupFields = (new FormatWithGroupFields($breakdown))->decorate();
            return response()->json($formatWithGroupFields);
        }

        $breakdowns = $formatter->format($item, 'trims_booking');

        return response()->json($breakdowns);
    }

    private function wantsStoredDetails($item): bool
    {
        return $item->sensitivity == request('sensitivity') && $item->details;
    }

    public function deleteImages(Request $request, ItemFormatter $formatter)
    {
        $item = TrimsBookingDetails::where($request->except('sensitivity'))->first();
        $careInstructions = isset($item) ? collect($item->details)->pluck('care_symbol')->values() : null;
        foreach ($careInstructions as $path) {
            FileUploadRemoveService::removeFile($path);
        }
        $details = collect($item['details'])->map(function ($item) {
            if (isset($item['care_symbol'])) {
                $item['care_symbol'] = null;
            }
            return $item;
        });
        $item['details'] = $details;
        $item->update();


        return response()->json($item);
    }
}
