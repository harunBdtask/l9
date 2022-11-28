<?php

namespace SkylarkSoft\GoRMG\Knitting\Actions\KnittingProgram;

use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\Knitting\Models\PlanningInfo;

class KnittingProgramInPlanInfoAction
{
    public static function handle($planInfoId)
    {
        $knittingProgramInPlaningInfo = KnittingProgram::query()->where('plan_info_id', $planInfoId)->get()->pluck('id')->toArray();

        PlanningInfo::query()->find($planInfoId)->update([
            'knitting_program_ids' => $knittingProgramInPlaningInfo
        ]);
    }
}
