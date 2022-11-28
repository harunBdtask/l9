<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use Symfony\Component\HttpFoundation\Response;

class OrderRepeatApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $factoryId = $request->get('factory_id');
        $buyerId = $request->get('buyer_id');
        $quotationId = $request->get('quotation_id');
        $style = $request->get('style');

        $orderData = Order::query()
            ->with(['factory', 'buyer'])
            ->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
            ->when($buyerId, Filter::applyFilter('buyer_id', $buyerId))
            ->when($quotationId, Filter::applyFilter('price_quotation_id', $quotationId))
            ->when($style, Filter::applyFilter('id', $style))
            ->get();

        return response()->json($orderData, Response::HTTP_OK);
    }
}
