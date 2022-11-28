<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\TrimsStore\Filters\Filter;
use Symfony\Component\HttpFoundation\Response;

class TrimsBookingNosApiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $bookingNos = TrimsBooking::query()
                ->where('buyer_id', $request->get('buyer_id'))
                ->whereHas('details', Filter::applyFilter('style_name', $request->get('style_name')))
                ->get(['id', 'unique_id as text']);

            return response()->json([
                'message' => 'Booking no fetched successfully',
                'data' => $bookingNos,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
