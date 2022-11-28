<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Inventory\Models\StoreFloor;

class StoreFloorsApiController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            if (request('storeId')){
                $storeFloors = StoreFloor::query()
                    ->where('store_id',request('storeId'))
                    ->orderByDesc('name')
                    ->get(['id', 'name']);
            }else{
                $storeFloors = StoreFloor::query()
                    ->orderByDesc('name')
                    ->get(['id', 'name']);
            }
            return response()->json($storeFloors, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
