<?php

namespace SkylarkSoft\GoRMG\Knitting\Actions\KnittingProduction;

use SkylarkSoft\GoRMG\Knitting\Models\KnitProgramRoll;
use SkylarkSoft\GoRMG\Knitting\Models\PlanningInfo;

class KnittingRollProductionQtyAction
{
    public static function handle($planInfoId)
    {
        $productionQty = KnitProgramRoll::query()->where('plan_info_id', $planInfoId)->sum('roll_weight');
        PlanningInfo::query()->find($planInfoId)->update(['production_qty' => $productionQty]);
    }
}
