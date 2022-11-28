<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsInventory;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventory;
use Symfony\Component\HttpFoundation\Response;

class TrimsInventoryBinNoApiController extends Controller
{
    public function __invoke($bookingId): JsonResponse
    {
        try {
            $inventoryNo = TrimsInventory::query()
                ->where('booking_id', $bookingId)
                ->get([
                    'id',
                    'bin_no as text'
                ]);

            return response()->json([
                'data' => $inventoryNo ?? [],
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
