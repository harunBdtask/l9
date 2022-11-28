<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use Symfony\Component\HttpFoundation\Response;

class FetchFabricBookingsApiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $company = $request->get('factory_id');

        $bookings = FabricBooking::query()
            ->where('factory_id', $company)
            ->get(['unique_id', 'id', 'fabric_source', 'level'])
            ->map(function ($booking) {
                return [
                    'id' => $booking['unique_id'],
                    'text' => $booking['unique_id']
                ];
            });

        return response()->json($bookings, Response::HTTP_OK);
    }
}
