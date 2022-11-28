<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget;

use SkylarkSoft\GoRMG\Merchandising\Actions\FeatureVersionAction;
use SkylarkSoft\GoRMG\Merchandising\Features;

class AssociateVersionWithOrder
{
    public static function attach($budgetId, $orderId)
    {
        $featureVersionAction = new FeatureVersionAction();
        $costings = [
            Features::BUDGET_FABRIC_COST,
            Features::BUDGET_TRIM_COST,
            Features::BUDGET_EMBELLISHMENT_COST,
            Features::BUDGET_WASH_COST,
        ];

        foreach ($costings as $cost) {
            $featureVersionAction->attach(
                $cost,
                $budgetId,
                Features::ORDER,
                $orderId
            );
        }


    }

    public static function detach($budgetId)
    {
        $featureVersionAction = new FeatureVersionAction();
        $costings = [
            Features::BUDGET_FABRIC_COST,
            Features::BUDGET_TRIM_COST,
            Features::BUDGET_EMBELLISHMENT_COST,
            Features::BUDGET_WASH_COST,
        ];

        foreach ($costings as $cost) {
            $featureVersionAction->detach($cost, $budgetId);
        }


    }
}
