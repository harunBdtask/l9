<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\CostingState;
use Symfony\Component\HttpFoundation\Response;

class BudgetCostingApiController extends Controller
{
    /**
     * @param $budgetId
     * @param $type
     * @return JsonResponse
     */
    public function __invoke($budgetId, $type): JsonResponse
    {
        try {
            $budget = BudgetCostingDetails::query()->where([
                'budget_id' => $budgetId,
                'type' => $type,
            ])->first();
            $state = CostingState::setState($type);
            $data = $state->format($budget, $budgetId, $type);

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getLine(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
