<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;
use Symfony\Component\HttpFoundation\Response;

class SupplierWiseSubTextileOrdersController extends Controller
{
    /**
     * @param $factoryId
     * @param $supplierId
     * @return JsonResponse
     */
    public function __invoke($factoryId, $supplierId): JsonResponse
    {
        try {
            $orders = SubTextileOrder::query()
                ->where('factory_id', $factoryId)
                ->where('supplier_id', $supplierId)
                ->get(['id', 'order_no as text']);

            return response()->json([
                'message' => 'Fetch textile order successfully',
                'data' => $orders,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
