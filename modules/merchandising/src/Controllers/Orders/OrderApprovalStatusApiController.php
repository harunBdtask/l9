<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use Symfony\Component\HttpFoundation\Response;

class OrderApprovalStatusApiController extends Controller
{
    /**
     * @param $orderId
     * @return JsonResponse
     */
    public function __invoke($orderId): JsonResponse
    {
        $purchaseOrders = PurchaseOrder::query()
            ->select(['po_no', 'is_approved'])
            ->where('order_id', $orderId)
            ->get();
        return response()->json($purchaseOrders, Response::HTTP_OK);
    }
}
