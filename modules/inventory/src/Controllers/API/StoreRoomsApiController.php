<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRoom;

class StoreRoomsApiController extends Controller
{
    public function index($floorId): JsonResponse
    {
        try {
            $storeRooms = StoreRoom::query()->where('floor_id', $floorId)->orderByDesc('name')->get();
            return response()->json($storeRooms, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
