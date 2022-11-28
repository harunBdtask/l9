<?php

namespace SkylarkSoft\GoRMG\Planing\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Planing\Services\PurchaseOrderService;
use Symfony\Component\HttpFoundation\Response;

class PurchaseOrderApiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $purchaseOrders = PurchaseOrderService::purchaseOrders($request);

            return response()->json([
                'message' => 'Fetch purchase orders successfully',
                'data' => [
                    [
                        'index' => 1,
                        'items' => $purchaseOrders,
                    ],
                ],
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
