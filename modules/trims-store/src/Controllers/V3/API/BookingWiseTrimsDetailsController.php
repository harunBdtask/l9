<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use Symfony\Component\HttpFoundation\Response;

class BookingWiseTrimsDetailsController extends Controller
{
    public function __invoke(TrimsBooking $trimsBooking): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Trims store receive detail stored successfully',
                'data' => $trimsBooking->load('details')->getRelation('details'),
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
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
