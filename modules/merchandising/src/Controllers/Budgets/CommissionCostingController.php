<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Requests\Budgets\CommissionCostRequest;
use Symfony\Component\HttpFoundation\Response;

class CommissionCostingController extends Controller
{
    const COSTING_TYPE = 'commission_cost';

    public function store(CommissionCostRequest $request): JsonResponse
    {
        try {
            $costing = BudgetCostingDetails::query()->firstOrNew([
                'budget_id' => $request->get('budget_id'),
                'type' => self::COSTING_TYPE,
            ]);

            $costing->details = [
                'details' => $request->input('commissionCostingForm'),
                'calculation' => $request->input('sumCommissionCosting'),
            ];

            $costing->save();

            return response()->json(['message' => 'Successfully Stored']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something Went Wrong!',
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getBudget($budgetId): JsonResponse
    {
        return response()->json(Budget::query()->find($budgetId), Response::HTTP_OK);
    }
}
