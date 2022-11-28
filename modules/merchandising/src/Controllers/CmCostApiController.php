<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Services\PriceQuotation\CostingMultiplierService;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinancialParameterSetup;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;
use Symfony\Component\HttpFoundation\Response;

class CmCostApiController extends Controller
{
    const CPM_WITH_SMV_AND_COSTING_PER = 6;
    const WRITEABLE = 1;

    public function __invoke($quotation): JsonResponse
    {
        try {
            $priceQuotation = PriceQuotation::query()->where('id', $quotation)->firstOrFail();
            $merchandisingVariable = MerchandisingVariableSettings::query()
                ->where([
                    'factory_id' => $priceQuotation->factory_id,
                    'buyer_id' => $priceQuotation->buyer_id
                ])->firstOr(function () {
                    return [
                        'variables_details' => [
                            'cm_cost_calculation_method_in_pq' => [
                                'method' => null,
                                'writeable' => null,
                            ]
                        ]
                    ];
                });
            $cmCost = 0;
            $cmCostCalculationMethod = $merchandisingVariable['variables_details']['cm_cost_calculation_method_in_pq'];
            if ($this->isCalculateAble($cmCostCalculationMethod)) {
                $totalSmv = $priceQuotation->item_details[count($priceQuotation->item_details) - 1]['total_smv'];
                $costPerMinute = $this->getCostPerMinute($priceQuotation);
                $costingPer = CostingMultiplierService::generate($priceQuotation->style_uom, $priceQuotation->costing_per);
                $cmCost = $this->calculateCmCost($totalSmv, $costPerMinute, $costingPer);
            }
            return response()->json([
                'cm_cost_val' => format($cmCost),
                'is_disable' => !$this->isWriteAble($cmCostCalculationMethod),
                'message' => 'cm cost calculated'
            ], Response::HTTP_OK);

        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function isWriteAble($cmCostCalculationMethod): bool
    {
        return $cmCostCalculationMethod['writeable'] == self::WRITEABLE;
    }

    private function isCalculateAble($cmCostCalculationMethod): bool
    {
        return ($cmCostCalculationMethod['method'] == self::CPM_WITH_SMV_AND_COSTING_PER
            && !$this->isWriteAble($cmCostCalculationMethod));
    }

    private function getCostPerMinute($priceQuotation): float
    {
        $quotationDate = date_format(date_create($priceQuotation->quotation_date), 'Y-m-d');
        $financialParameter = FinancialParameterSetup::query()
            ->where("factory_id", $priceQuotation->factory_id)
            ->where("date_from", "<=", $quotationDate)
            ->where("date_to", ">=", $quotationDate)
            ->first();

        return $financialParameter->cost_per_minute ?? 0.00;
    }

    private function calculateCmCost($totalSmv, $costPerMinute, $costingPer)
    {
        return ($totalSmv * $costPerMinute * $costingPer);
    }
}
