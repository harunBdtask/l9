<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Services\AskingProfitCalculationService;
use SkylarkSoft\GoRMG\SystemSettings\Services\CmCostCalculationMethodService;
use SkylarkSoft\GoRMG\SystemSettings\Services\CommercialCostMethodService;
use SkylarkSoft\GoRMG\SystemSettings\Services\FabricPartCopyService;
use SkylarkSoft\GoRMG\SystemSettings\Services\StyleSmvSourceService;

class VariableSettingsApiController extends Controller
{
    public function variableData(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data['style_smv_source'] = StyleSmvSourceService::all();
            $data['fabric_part_copy_data'] = FabricPartCopyService::all();
            $data['commercial_cost_method'] = CommercialCostMethodService::all();
            $data['cm_cost_calculation_method'] = CmCostCalculationMethodService::all();
            $data['asking_profit_calculations'] = AskingProfitCalculationService::all();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getBuyers($factoryId): \Illuminate\Http\JsonResponse
    {
        $buyers = Buyer::query()
            ->filterWithAssociateFactory('buyerWiseFactories', $factoryId)
            ->get(['id', 'name']);

        return response()->json($buyers, Response::HTTP_OK);
    }
}
