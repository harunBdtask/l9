<?php

namespace SkylarkSoft\GoRMG\HR\Repositories;


use SkylarkSoft\GoRMG\HR\Models\HrAttendanceSummary;

class NightOtRepository
{
    public function paginate()
    {
        return HrAttendanceSummary::with('employeeOfficialInfo')
            ->where('night_ot_eligible_status', HrAttendanceSummary::NIGHT_OT_ELIGIBLE)
            ->orderBy('id', 'desc')
            ->paginate();
    }
}
