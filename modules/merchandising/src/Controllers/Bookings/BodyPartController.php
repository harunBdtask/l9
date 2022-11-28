<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\Fabric\BodyPartsService;
use Symfony\Component\HttpFoundation\Response;

class BodyPartController extends Controller
{
    public function bodyParts(Request $request): JsonResponse
    {
        try {
            $bookingJobNos = FabricBookingDetailsBreakdown::query()
                ->where('booking_id', $request->get('id'))
                ->pluck('job_no')
                ->unique();

            $data = BodyPartsService::getBodyParts($bookingJobNos);

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
