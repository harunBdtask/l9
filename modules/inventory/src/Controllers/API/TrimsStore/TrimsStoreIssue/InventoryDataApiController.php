<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreIssue;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventory;

class InventoryDataApiController extends Controller
{
    public function __invoke($id): JsonResponse
    {
        try {
            $inventoryNo = TrimsInventory::query()
                ->where('id', $id)
                ->first([
                    'id',
                    'challan_no as text'
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
