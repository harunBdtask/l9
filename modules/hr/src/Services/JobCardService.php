<?php

namespace SkylarkSoft\GoRMG\HR\Services;

use SkylarkSoft\GoRMG\HR\Models\HrAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;

class JobCardService
{
    public function extractYearAndMonth($value)
    {
        return explode('-', $value);
    }

    public function attendanceSummeryForUserInDate($userId, $date)
    {
        return HrAttendanceSummary::where('userid', $userId)->whereDate('date', $date->toDateString())->first();
    }

    public function fetchEmployeeWithId($id)
    {
        return HrEmployee::with([
            "officialInfo.departmentDetails",
            "officialInfo.designationDetails",
            "officialInfo.sectionDetails"
        ])->find($id);
    }

    /**
     * @param $data
     * @return array
     */
    public function emptyData($data)
    {
        $data['intime'] = null;
        $data['outtime'] = null;
        $data['totalhour'] = null;
        $data['lunchhour'] = null;
        $data['minute'] = null;
        $data['main_ot'] = null;
        $data['extraot'] = null;
        $data['late'] = null;

        return $data;
    }
}
