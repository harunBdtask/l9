<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsInventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsStore\BookingDataApiService;
use Symfony\Component\HttpFoundation\Response;

class BookingDataApiController extends Controller
{
    public function __invoke($bookingNo): JsonResponse
    {
        try {
            $data = BookingDataApiService::get($bookingNo);
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
