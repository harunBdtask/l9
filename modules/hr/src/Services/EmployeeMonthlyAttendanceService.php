<?php

namespace SkylarkSoft\GoRMG\HR\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Models\HrMonthlyPaymentSummary;

class EmployeeMonthlyAttendanceService
{

    public static function getEmployeeInformation(Request $request)
    {

        $departmentId = $request->department_id ?? null;
        $sectionId = $request->section_id ?? null;
        $type = $request->type ?? null;
        $month = $request->month;
        $year = $request->year;


        $query = HrEmployeeOfficialInfo::where(['department_id' => $departmentId]);
        if ($sectionId) {
            $query->where(['section_id' => $sectionId]);
        }
        $userIds = $query->pluck('unique_id')->toArray();
        $data['no_of_days'] = Carbon::parse($year . '-' . $month . '-01')->daysInMonth;

        $data['summaries'] = HrMonthlyPaymentSummary::with('employee', 'employeeOfficialInfo', 'employeeOfficialInfo.designationDetails')
            ->whereIn('userid', $userIds)
            ->whereMonth('pay_month', $month)
            ->whereYear('pay_month', $year)
            ->when($type != null, function ($query) use ($type) {
                return $query->whereHas('employeeOfficialInfo', function ($query) use ($type) {
                    return $query->where('type', $type);
                });
            })
            ->get();

        return $data;
    }
}
