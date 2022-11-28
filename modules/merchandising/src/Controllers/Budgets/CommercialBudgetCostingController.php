<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Requests\Budgets\CommercialCostRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\CommercialCostMethod;
use Symfony\Component\HttpFoundation\Response;

class CommercialBudgetCostingController extends Controller
{
    public function getTypes(): JsonResponse
    {
        return response()->json(CommercialCostMethod::all());
    }

    /**
     * @param CommercialCostRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(CommercialCostRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $embellishment_cost = BudgetCostingDetails::firstOrCreate([
                'budget_id' => $request->get('budget_id'),
                'type' => 'commercial_cost',
            ]);
            $requested_data = $request->all();
            $details = [];

            // details form
            $details['details'] = $request->get('commercialCostingForm');

            // form wise calculation
            $details['calculation'] = $request->get('sumCommercialCosting');
            $requested_data['details'] = $details;
            $embellishment_cost->fill($requested_data)->save();
            DB::commit();
            $response = [
                "status" => Response::HTTP_OK,
                "message" => "Budget Commercial Cost added successfully",
                "type" => "success",
                "data" => $requested_data,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
