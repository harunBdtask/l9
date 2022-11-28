<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;

class BuyerWiseSubTextileOrderController extends Controller
{
    /**
     * @param $factoryId
     * @param $buyerId
     * @return JsonResponse
     */
    public function __invoke($factoryId, $buyerId): JsonResponse
    {
        try {
            $orders = TextileOrder::query()
                ->where('factory_id', $factoryId)
                ->where('buyer_id', $buyerId)
                ->get(['id', 'fabric_sales_order_no as text']);

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
