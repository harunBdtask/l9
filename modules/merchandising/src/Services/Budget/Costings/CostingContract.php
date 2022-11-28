<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings;

interface CostingContract
{
    public function format($budget, $budgetId, $type);
}
