<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder;

class FabricSalesOrdersNoApiController extends Controller
{

    const YES = 1;

    /**
     * @param $buyerId
     * @return JsonResponse
     */
    public function __invoke($buyerId): JsonResponse
    {
        try {
            $fabricSalesOrdersNo = FabricSalesOrder::query()
                ->where('within_group', self::YES)
                ->where('buyer_id', $buyerId)
                ->get(['id', 'sales_order_no as text']);

            return response()->json([
                'message' => 'Fetch fabric sales orders no successfully',
                'data' => $fabricSalesOrdersNo,
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
