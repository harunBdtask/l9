<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Requests\Budgets\FabricCostingRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetCostingService;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinancialParameterSetup;
use Symfony\Component\HttpFoundation\Response;

class BudgetCostingController extends Controller
{
    private $budgetCostingService;

    public function __construct(BudgetCostingService $budgetCostingService)
    {
        $this->budgetCostingService = $budgetCostingService;
    }

    public function getCostingTypes(): JsonResponse
    {
        try {
            $costing_types = $this->budgetCostingService->costingTypes();
            return response()->json($costing_types, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getFabricColors(): JsonResponse
    {
        try {
            $fabric_colors = $this->budgetCostingService->fabricColors();

            return response()->json($fabric_colors, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $budgetId
     * @return JsonResponse
     */
    public function getBudgetData($budgetId): JsonResponse
    {
        try {
            $budget_data = Budget::query()
                ->with([
                    'order.priceQuotation',
                    'buyer:id,name',
                    'order.purchaseOrders.poDetails',
                    'costings'])
                ->findOrFail($budgetId);
            $response = [
                'budget' => $budget_data,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $budget = Budget::query()->findOrFail($request->get('budget_id'));
            $budget->update([
                'costing' => $request->get('costing'),
            ]);

            return response()->json($budget, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param FabricCostingRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function fabricCostingStore(FabricCostingRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $fabric_cost = BudgetCostingDetails::query()->where('budget_id', $request->get('budget_id'))
                ->where('type', 'fabric_costing')
                ->first();
            $requested_data = $request->all();
            $details = [];

            // details form
            $details['details']['fabricForm'] = $request->get('fabricForm');
            $details['details']['yarnCostForm'] = $request->get('yarnCostForm');
            $details['details']['conversionCostForm'] = $request->get('conversionCostForm');

            // form wise calculation
            $details['calculation']['fabric_costing'] = $request->get('fabricCalculation');
            $details['calculation']['yarn_costing'] = $request->get('yarnCalculation');
            $details['calculation']['conversion_costing'] = $request->get('conversionCalculation');
            $requested_data['details'] = $details;
            if (!$fabric_cost) {
                $fabric_cost = new BudgetCostingDetails();
                $requested_data['budget_id'] = $request->get('budget_id');
                $requested_data['type'] = 'fabric_costing';
            }
            $fabric_cost->fill($requested_data)->save();
            DB::commit();
            $response = [
                "status" => Response::HTTP_OK,
                "message" => "Budget Fabric Cost added successfully",
                "type" => "success",
                "data" => $requested_data,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCmCost($budgetId)
    {
        $budget = Budget::query()
            ->with('order')
            ->findOrFail($budgetId);
        $costingData = date_format(date_create($budget->costing_date), 'Y-m-d');

        $smv = $budget->order->smv;
        $sewingEff = $budget->sew_efficiency ?? 0;
        $costingMulti = $budget->costing_multiplier;
        $totalItemRatio = $budget->order->item_details['calculation']['total_item_ratio'];

        $cpm = FinancialParameterSetup::where("factory_id", $budget->factory_id)
            ->where("date_from", "<=", $costingData)
            ->where("date_to", ">=", $costingData)
            ->first();

        // v6
        $sewingEffDiv = $sewingEff != 0 ? (100 / $sewingEff) : 0;
//        $cmCost = (($cpm->cost_per_minute * (100 / $sewingEff)) * $smv) * $costingMulti * $totalItemRatio;
        $cmCost = $sewingEffDiv != 0 ?(($cpm->cost_per_minute * $sewingEffDiv) * $smv) * $costingMulti * $totalItemRatio : 0;

        return response()->json([
            'budget' => $budget,
            'smv' => $smv,
            'sewingEff' => $sewingEff,
            'cpm' => $cpm,
            'costing_date' => $costingData,
            'cm_cost' => $cmCost,
            'costing_multi' => $costingMulti,
            'total_item_ratio' => $totalItemRatio,
        ]);
    }
}
