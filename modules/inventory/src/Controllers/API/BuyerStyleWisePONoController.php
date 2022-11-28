<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class BuyerStyleWisePONoController extends Controller
{
    public function __invoke($buyerId, $styleId): JsonResponse
    {
        try {
            $poNo = PurchaseOrder::query()->where('buyer_id', $buyerId)
                ->where('order_id', $styleId)
                ->get()->map(function ($purchaseOrder) {
                    return [
                        'id' => $purchaseOrder->id,
                        'text' => $purchaseOrder->po_no,
                    ];
                });

            return response()->json($poNo, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
