<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\PriceQuotation\Costings;

class FabricCostingFormatter implements CostingFormatterContract
{
    public function format($budgetId, $details): array
    {
        $response = [];
        $response['details'] = $details['details'];
        $detailsCalculation = $details['calculation'];
        $response['calculation'] = [
            'yarn_costing' => [
                'yarn_cons_sum' => $detailsCalculation['sumYarnCosting']['yarn_cons_sum'] ?? 0,
                'yarn_rate_sum' => $detailsCalculation['sumYarnCosting']['yarn_rate_sum'] ?? 0,
                'yarn_amount_sum' => $detailsCalculation['sumYarnCosting']['yarn_amount_sum'] ?? 0,
            ],
            'fabric_costing' => [
                'grey_cons_sum' => $detailsCalculation['sumFabricCosting']['grey_cons_sum'] ?? 0,
                'total_amount_sum' => $detailsCalculation['sumFabricCosting']['amount_sum'] ?? 0,
                'grey_cons_rate_sum' => $detailsCalculation['sumFabricCosting']['grey_cons_rate_sum'] ?? 0,
                'grey_cons_amount_sum' => $detailsCalculation['sumFabricCosting']['grey_cons_amount_sum'] ?? 0,
                'grey_cons_total_amount_sum' => $detailsCalculation['sumFabricCosting']['grey_cons_total_amount_sum'] ?? 0,
                'grey_cons_total_quantity_sum' => $detailsCalculation['sumFabricCosting']['grey_cons_total_quantity_sum'] ?? 0
            ],
            'conversion_costing' => [
                'conversion_unit' => $detailsCalculation['sumConversionCosting']['conversion_unit'] ?? 0,
                'conversion_amount_sum' => $detailsCalculation['sumConversionCosting']['conversion_amount_sum'] ?? 0
            ],
        ];
        return collect($response)->toArray();
    }
}
