<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Requests\Budgets\WashCostRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class WashBudgetCostingController extends Controller
{
    /**
     * @param WashCostRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(WashCostRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $embellishment_cost = BudgetCostingDetails::query()->firstOrCreate([
                'budget_id' => $request->get('budget_id'),
                'type' => 'wash_cost',
            ]);
            $requested_data = $request->all();
            $details = [];

            // details form
            $details['details'] = $request->get('washCostingForm');

            // form wise calculation
            $details['calculation'] = $request->get('sumWashCosting');
            $requested_data['details'] = $details;
            $embellishment_cost->fill($requested_data)->save();
            DB::commit();
            $response = [
                "status" => Response::HTTP_OK,
                "message" => "Budget Wash Cost added successfully",
                "type" => "success",
                "data" => $requested_data,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
