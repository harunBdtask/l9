<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApproveService;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\POItemColorSizeBreakdownService;
use Symfony\Component\HttpFoundation\Response;

class BudgetGreyConsBreakdownController extends Controller
{
    /**
     * @param Request $request
     * @param $budgetId
     * @param $itemId
     * @return JsonResponse
     */
    public function loadItemWiseBreakdown(Request $request, $budgetId, $itemId): JsonResponse
    {
        try {

            $breakDown = ApproveService::checkFor(POItemColorSizeBreakdownService::fabric($request, $budgetId, $itemId))
                ->variableCheckByBudget($budgetId)
                ->get();

            return response()->json([
                'status' => 'success',
                'type' => 'purchase Order & item wise po details',
                'breakdown' => $breakDown,
                'data' => $request->all(),
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'success',
                'type' => 'purchase Order & item wise po details',
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'exception' => $exception,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
