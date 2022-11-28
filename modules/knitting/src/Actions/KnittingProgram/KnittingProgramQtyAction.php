<?php

namespace SkylarkSoft\GoRMG\Knitting\Actions\KnittingProgram;

use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramColorsQty;
use SkylarkSoft\GoRMG\Knitting\Models\PlanningInfo;

class KnittingProgramQtyAction
{
    public static function handle($planInfoId)
    {
        $programQty = KnittingProgramColorsQty::query()->where('plan_info_id', $planInfoId)->sum('program_qty');
        PlanningInfo::query()->find($planInfoId)->update(['program_qty' => $programQty]);
    }
}
