<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\Stores;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\Inventory\Models\StoreFloor;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRoom;
use SkylarkSoft\GoRMG\Inventory\Requests\StoreRoomRequest;

class StoreRoomController extends Controller
{
    public function store(Store $store, StoreFloor $floor, StoreRoomRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $id = $request->input('id');
            $message  = '';
            if ($id) {
                $floor->rooms()->find($id)->update($request->all());
                $message  = 'Data updated successfully!';
            } else {
                $floor->rooms()->create($request->all());
                $message  = 'Data stored successfully!';
            }
            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => $message,
                'error' => null
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => "Something went wrong!",
                'error' => $e->getMessage()
            ]);
        }
    }

    public function destroy(StoreRoom $room): JsonResponse
    {
        try {
            DB::beginTransaction();
            $message  = 'Data deleted successfully!';
            $room->delete();
            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => $message,
                'error' => null
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => "Something went wrong!",
                'error' => $e->getMessage()
            ]);
        }
    }
}
