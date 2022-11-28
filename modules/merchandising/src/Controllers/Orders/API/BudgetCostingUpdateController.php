<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Merchandising\Actions\FeatureVersionAction;
use SkylarkSoft\GoRMG\Merchandising\Features;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\BudgetUpdateServices\EmbellishmentUpdateService;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\BudgetUpdateServices\FabricUpdateService;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\BudgetUpdateServices\TrimsUpdateService;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\BudgetUpdateServices\WashService;
use Symfony\Component\HttpFoundation\Response;

class BudgetCostingUpdateController extends Controller
{

    /**
     * @param $orderId
     * @param FeatureVersionAction $featureVersionAction
     * @return JsonResponse
     */
    public function fabricCosting($orderId, FeatureVersionAction $featureVersionAction): JsonResponse
    {
        try {
            $budget = Budget::query()->where('copy_from_id', $orderId)->first();
            FabricUpdateService::update($orderId);
            $response = [
                'message' => 'Fabric Costing Updated',
                'status' => Response::HTTP_OK
            ];
            if ($budget) {
                $featureVersionAction->attach(
                    Features::BUDGET_FABRIC_COST,
                    $budget->id,
                    Features::ORDER,
                    $orderId
                );
            }
            return response()->json($response, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    /**
     * @param $orderId
     * @param FeatureVersionAction $featureVersionAction
     * @return JsonResponse
     */
    public function trimCosting($orderId, FeatureVersionAction $featureVersionAction): JsonResponse
    {
        try {
            $budget = Budget::query()->where('copy_from_id', $orderId)->first();
            TrimsUpdateService::update($orderId);
            $response = [
                'message' => 'Trims Costing Updated',
                'status' => Response::HTTP_OK
            ];
            if ($budget) {
                $featureVersionAction->attach(
                    Features::BUDGET_TRIM_COST,
                    $budget->id,
                    Features::ORDER,
                    $orderId
                );
            }
            return response()->json($response, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    /**
     * @param $orderId
     * @param FeatureVersionAction $featureVersionAction
     * @return JsonResponse
     */
    public function embellishmentCosting($orderId, FeatureVersionAction $featureVersionAction): JsonResponse
    {
        try {
            $budget = Budget::query()->where('copy_from_id', $orderId)->first();
            EmbellishmentUpdateService::update($orderId);
            $response = [
                'message' => 'Embellishment Costing Updated',
                'status' => Response::HTTP_OK
            ];
            if ($budget) {
                $featureVersionAction->attach(
                    Features::BUDGET_EMBELLISHMENT_COST,
                    $budget->id,
                    Features::ORDER,
                    $orderId
                );
            }
            return response()->json($response, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * @param $orderId
     * @param FeatureVersionAction $featureVersionAction
     * @return JsonResponse
     */

    public function washCosting($orderId, FeatureVersionAction $featureVersionAction): JsonResponse
    {
        try {
            $budget = Budget::query()->where('copy_from_id', $orderId)->first();
            WashService::update($orderId);
            $response = [
                'message' => 'Wash Costing Updated',
                'status' => Response::HTTP_OK
            ];
            if ($budget) {
                $featureVersionAction->attach(
                    Features::BUDGET_WASH_COST,
                    $budget->id,
                    Features::ORDER,
                    $orderId
                );
            }
            return response()->json($response, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
