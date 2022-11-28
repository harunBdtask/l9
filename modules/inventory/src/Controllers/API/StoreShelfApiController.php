<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Inventory\Models\StoreShelf;

class StoreShelfApiController extends Controller
{
    public function index($floorId, $roomId, $rackId): JsonResponse
    {
        try {
            $storeShelf = StoreShelf::query()
                ->where('floor_id', $floorId)
                ->where('room_id', $roomId)
                ->where('rack_id', $rackId)
                ->orderByDesc('name')
                ->get();

            return response()->json($storeShelf, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
