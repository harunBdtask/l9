<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Services\PriceQuotation\Costings\CostingAdapter;

class CopyPriceQuotationCostingsForBudget
{
    public static function handle($priceQuotations, $budgetId)
    {
        if (!empty($priceQuotations)) {
            foreach ($priceQuotations as $priceQuotation) {
                $detailsFormatAdapter = CostingAdapter::setState($priceQuotation['type'])
                    ->setBudgetId($budgetId)
                    ->doFormat($priceQuotation['details']);
                BudgetCostingDetails::query()->create([
                    'budget_id' => $budgetId,
                    'type' => $priceQuotation['type'],
                    'details' => $detailsFormatAdapter
                ]);
            }
        }
    }
}
