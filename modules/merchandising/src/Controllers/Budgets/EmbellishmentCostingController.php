<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Requests\Budgets\EmbellishmentCostRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApproveService;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetCostingService;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\POItemColorSizeBreakdownService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class EmbellishmentCostingController extends Controller
{
    private $budgetCostingService;

    public function __construct(BudgetCostingService $budgetCostingService)
    {
        $this->budgetCostingService = $budgetCostingService;
    }

    public function bodyPart($budgetId): JsonResponse
    {
        $fabric_costing = BudgetCostingDetails::query()
            ->where('type', BudgetCostingDetails::FABRIC_COSTING)
            ->where("budget_id", $budgetId)
            ->first();
        $costing_details = $fabric_costing->details['details']['fabricForm'] ?? [];
        $details = collect($costing_details)->pluck('body_part_value', 'body_part_id')->map(function ($value, $key) {
            return [
                "id" => $key,
                "text" => $value,
            ];
        })->values();

        return response()->json($details, Response::HTTP_OK);
    }

    public function getCountries(): JsonResponse
    {
        return response()->json($this->budgetCostingService->countries(), Response::HTTP_OK);
    }

    public function poItemColorSizeBreakdown($budgetId, Request $request): JsonResponse
    {
        try {
            $breakdown = ApproveService::checkFor(POItemColorSizeBreakdownService::embellishment($budgetId, $request))
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

    /**
     * @param EmbellishmentCostRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(EmbellishmentCostRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $embellishment_cost = BudgetCostingDetails::query()
                ->firstOrCreate([
                    'budget_id' => $request->get('budget_id'),
                    'type' => 'embellishment_cost',
                ]);
            $requested_data = $request->all();
            $details = [];

            // details form
            $details['details'] = $request->get('embellishmentCostingForm');

            // form wise calculation
            $details['calculation'] = $request->get('sumEmbellishmentCosting');
            $requested_data['details'] = $details;
            $embellishment_cost->fill($requested_data)->save();
            DB::commit();
            $response = [
                "status" => Response::HTTP_OK,
                "message" => "Budget Embellishment Cost added successfully",
                "type" => "success",
                "data" => $requested_data,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
