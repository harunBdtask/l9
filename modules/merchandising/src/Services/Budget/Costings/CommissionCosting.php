<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings;

class CommissionCosting implements CostingContract
{
    public function format($budget, $budgetId, $type)
    {
        return $budget;
    }
}
