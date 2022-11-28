<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreMrr;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventory;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceive;
use Symfony\Component\HttpFoundation\Response;

class TrimsReceiveApiController extends Controller
{
    public function __invoke($receiveId): JsonResponse
    {
        try {
            $trimsReceive = TrimsStoreReceive::query()
                ->withSum('details','total_receive_amount')
                ->where('id', $receiveId)
                ->first();
            $trimsInventory = TrimsInventory::query()
                ->where('id', $trimsReceive->trims_inventory_id)
                ->first();
            return response()->json([
                'data' => $trimsInventory,
                'trimsReceive' => $trimsReceive,
                'status' => Response::HTTP_OK,
                'message' => \SUCCESS_MSG,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => \SOMETHING_WENT_WRONG,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }
}
