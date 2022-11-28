<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use Symfony\Component\HttpFoundation\Response;

class UsedStyleApiController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        try {
            $usedStyle = Order::query()
                ->select(['style_name'])
                ->distinct()
                ->get()
                ->map(function ($collection) {
                    return $collection->style_name;
                });
            return response()->json($usedStyle, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
