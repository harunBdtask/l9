<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Inventory\Models\StoreBin;

class StoreBinsApiController extends Controller
{
    public function index($floorId, $roomId, $rackId, $shelfId): JsonResponse
    {
        try {
            $storeBins = StoreBin::query()
                ->where('floor_id', $floorId)
                ->where('room_id', $roomId)
                ->where('rack_id', $rackId)
                ->where('shelf_id', $shelfId)
                ->orderByDesc('name')
                ->get();

            return response()->json($storeBins, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
