<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\PriceQuotation\Costings;

class TrimsCostingFormatter implements CostingFormatterContract
{
    public function format($budgetId, $details): array
    {
        $details['details'] = collect($details['details'])->map(function ($value) {
            $value['description'] = $value['item_description'];
            return $value;
        });

        return collect($details)->toArray();
    }
}
