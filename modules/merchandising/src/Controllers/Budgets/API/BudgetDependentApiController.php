<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetService;
use SkylarkSoft\GoRMG\Merchandising\Services\Factory\FactoryService;
use SkylarkSoft\GoRMG\Merchandising\Services\OrderService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Incoterm;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;

class BudgetDependentApiController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function loadCommonData(): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'type' => 'All Factories',
            'costing_per' => PriceQuotation::COSTING_PER,
            'regions' => PriceQuotation::REGIONS,
            'currencies' => Currency::all(),
            'incoterms' => Incoterm::all(),
            'product_departments' => ProductDepartments::all(),
            'style_uoms' => PriceQuotation::STYLE_UOM,
            'buying_agents' => BuyingAgentModel::query()
                ->with('buyingAgentWiseFactories')
                ->withoutGlobalScopes()
                ->filterWithAssociateFactory('buyingAgentWiseFactories', factoryId())
                ->get(),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getFactories(): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'type' => 'All Factories',
            'data' => FactoryService::getAllFactories(),
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getBuyers($id): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'type' => 'All Buyers',
            'data' => Buyer::query()->filterWithAssociateFactory('buyerWiseFactories', $id)->get(),
        ]);
    }

    /**
     * @param $factoryId
     * @param $buyerId
     * @return JsonResponse
     */
    public function getJobs($factoryId, $buyerId): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'type' => 'Factory And Buyer Wise Job',
            'data' => OrderService::loadFactoryBuyerWiseJob($factoryId, $buyerId),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function jobSearch(Request $request): JsonResponse
    {
        $request->validate([
            'factoryId' => 'required|numeric|min:0|not_in:0',
            'buyerId' => 'required|numeric|min:0|not_in:0',
        ]);

        return response()->json([
            'status' => 'Success',
            'type' => 'JOB Data',
            'data' => BudgetService::jobSearch($request),
        ]);
    }
}
