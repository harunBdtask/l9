<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use Symfony\Component\HttpFoundation\Response;

class OrderAssociateBudgetApiController extends Controller
{
    public function __invoke($orderId): JsonResponse
    {
        try {
            $budget = Budget::query()->where('copy_from_id', $orderId)->first();
            return response()->json($budget, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
