<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRack;

class StoreRacksApiController extends Controller
{
    public function index($floorId, $roomId): JsonResponse
    {
        try {
            $storeRacks = StoreRack::query()
                ->where('floor_id', $floorId)
                ->where('room_id', $roomId)
                ->orderByDesc('name')
                ->get();

            return response()->json($storeRacks, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
