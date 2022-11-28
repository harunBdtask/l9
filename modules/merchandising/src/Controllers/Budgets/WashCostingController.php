<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApproveService;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\POItemColorSizeBreakdownService;
use Symfony\Component\HttpFoundation\Response;

class WashCostingController extends Controller
{
    public function poItemColorSizeBreakdown($budgetId, Request $request): JsonResponse
    {
        try {
            $breakdown = ApproveService::checkFor(POItemColorSizeBreakdownService::wash($budgetId, $request))
                ->variableCheckByBudget($budgetId)
                ->get();

            return response()->json([
                'status' => 'success',
                'breakdown' => $breakdown,
                'data' => $request->all(),
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'success',
                'message' => $exception->getMessage(),
                'data' => $request->all(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
