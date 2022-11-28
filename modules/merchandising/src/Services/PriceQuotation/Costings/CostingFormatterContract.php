<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\PriceQuotation\Costings;

interface CostingFormatterContract
{
    public function format($budgetId, $details): array;
}
