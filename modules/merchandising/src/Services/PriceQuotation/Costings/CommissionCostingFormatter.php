<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\PriceQuotation\Costings;

class CommissionCostingFormatter implements CostingFormatterContract
{
    public function format($budgetId, $details): array
    {
        return collect($details)->toArray();
    }
}
