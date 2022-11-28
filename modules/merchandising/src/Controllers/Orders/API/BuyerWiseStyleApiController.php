<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\API;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use Symfony\Component\HttpFoundation\Response;

class BuyerWiseStyleApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $buyerId = $request->get('buyer_id');
            $styles = Order::query()->where('buyer_id',$buyerId)->get(['id', 'style_name as text']);

            return response()->json([
                'data' => $styles ?? [],
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
