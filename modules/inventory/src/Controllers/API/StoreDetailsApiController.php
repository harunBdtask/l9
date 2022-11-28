<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Inventory\Models\StoreDetail;

class StoreDetailsApiController extends Controller
{
    public function previousStoreDetails(): JsonResponse
    {
        try {
            $storeDetails = StoreDetail::query()->orderBy('id', 'desc')->get();

            return response()->json($storeDetails, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $storeDetails = StoreDetail::query()->with(['factory', 'store'])
                ->orderBy('id', 'desc')
                ->get();

            return response()->json($storeDetails, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            foreach ($request->all() as $value) {
                if (array_key_exists('id', $value) && $id = $value['id']) {
                    StoreDetail::query()->find($id)->update($value);

                    continue;
                }

                StoreDetail::query()->create($value);
            }

            return response()->json(['message' => 'Data saved successfully'], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(StoreDetail $storeDetail): JsonResponse
    {
        try {
            $storeDetail->delete();

            return response()->json(['message' => 'Successfully deleted'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getStoreOptions()
    {
        return StoreDetail::query()
            ->orderBy('floor_sequence')
            ->orderBy('room_sequence')
            ->orderBy('rack_sequence')
            ->orderBy('shelf_sequence')
            ->orderBy('bin_sequence')
            ->get(['floor', 'room', 'rack', 'shelf', 'bin', 'id'])
            ->groupBy(['floor', 'room', 'rack', 'shelf', 'bin']);
    }
}