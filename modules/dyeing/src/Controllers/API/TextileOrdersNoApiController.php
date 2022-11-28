<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;

class TextileOrdersNoApiController extends Controller
{

    /**
     * @param $textileOrderId
     * @return JsonResponse
     */
    public function __invoke($textileOrderId): JsonResponse
    {
        try {
            $textileOrdersNo = TextileOrder::query()
                ->where('factory_id', $textileOrderId)
                ->get(['id', 'factory_id', 'unique_id as text']);

            return response()->json([
                'message' => 'Fetch orders no successfully',
                'data' => $textileOrdersNo,
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
