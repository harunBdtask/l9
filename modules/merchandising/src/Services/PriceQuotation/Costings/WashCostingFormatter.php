<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\PriceQuotation\Costings;

class WashCostingFormatter implements CostingFormatterContract
{
    public function format($budgetId, $details): array
    {
        $response = [];
        $response['details'] = $details['details'];
        $detailsCalculation = $details['calculation'];
        $response['calculation'] = [
            'consumption_sum' => $detailsCalculation['amount_sum'] ?? 0,
            'consumption_rate_sum' => $detailsCalculation['rate_sum'] ?? 0,
            'consumption_amount_sum' => $detailsCalculation['amount_sum'] ?? 0
        ];

        return collect($response)->toArray();
    }
}
