<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class StyleApiController extends Controller
{
    public function __invoke(int $buyerId): JsonResponse
    {
        try {
            $styles = Order::query()->where('buyer_id', $buyerId)->get()->map(function ($order) {
                return [
                    'id' => $order->id,
                    'text' => $order->style_name,
                ];
            });

            return response()->json($styles, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
