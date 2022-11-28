<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreDeliveryChallan;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreDeliveryChallan\TrimsStoreDeliveryChallan;
use Symfony\Component\HttpFoundation\Response;

class DeliveryChallanWiseBookingNoApiController extends Controller
{
    public function __invoke($challanNo): JsonResponse
    {
        try {
            $query = TrimsStoreDeliveryChallan::query()
                ->where('challan_no', $challanNo);

            $bookingNos = $query->pluck('booking_no')->toArray();
            $challanType = $query->pluck('challan_type')->unique()->values()->join(', ');

            return response()->json([
                'message' => 'Booking Nos Fetched Successfully',
                'data' => ['bookingNos' => $bookingNos, 'challanType' => $challanType],
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
