<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings;

class EmbellishmentCosting implements CostingContract
{
    public function format($budget, $budgetId, $type)
    {
        return $budget;
    }
}
