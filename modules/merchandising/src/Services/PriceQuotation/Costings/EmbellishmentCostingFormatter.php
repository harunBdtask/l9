<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\PriceQuotation\Costings;

use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\POItemColorSizeBreakdownService;

class EmbellishmentCostingFormatter implements CostingFormatterContract
{

    public function format($budgetId, $details): array
    {
        $response = [];
        $response['details'] = collect($details['details'])->map(function ($collection) use ($budgetId) {

            $request = [];
            $request['cons_gmts'] = $collection['cons_per_dzn'];
            $request['rate'] = $collection['rate'];
            $request['amount'] = $collection['amount'];
            $breakDown = POItemColorSizeBreakdownService::embellishment($budgetId, $request);
            $collection['breakdown']['details'] = $breakDown;
            $rowCount = collect($breakDown)->count();
            $consumptionSum = collect($breakDown)->sum('cons_gmts');
            $rateSum = collect($breakDown)->sum('rate');
            $amountSum = collect($breakDown)->sum('amount');
            $collection['consumption'] = format((1 * $consumptionSum / $rowCount));
            $collection['consumption_rate'] = format((1 * $rateSum / $rowCount));
            $collection['consumption_amount'] = format((1 * $amountSum / $rowCount));

            return $collection;
        })->toArray();
        $detailsCalculation = $details['calculation'];
        $response['calculation'] = [
            'consumption_sum' => $detailsCalculation['amount_sum'] ?? 0,
            'consumption_rate_sum' => $detailsCalculation['rate_sum'] ?? 0,
            'consumption_amount_sum' => $detailsCalculation['amount_sum'] ?? 0
        ];

        return collect($response)->toArray();
    }
}
