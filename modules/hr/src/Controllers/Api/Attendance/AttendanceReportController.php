<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;

class AttendanceReportController
{
    public function dailyAttendanceSummary(Request $request){
        $date = $request->date ?? date('Y-m-d');
        $department_id = $request->department_id ?? null;
        $section_id = $request->section_id ?? null;
        $type = $request->type ?? null;

        $reports = $this->dailyAttendanceSummaryData($date, $department_id, $section_id, $type);

        return view('hr::reports.daily_attendance_summary_report_v2', [
            'reports' => $reports,
            'date' => $date,
        ])->render();
    }

    private function dailyAttendanceSummaryData($date, $department_id = '', $section_id = '', $type = '')
    {
        if ($date == '' || $department_id == '' || $section_id == '') {
            return null;
        }
        $user_ids = HrEmployeeOfficialInfo::query()
            ->where([
            'department_id' => $department_id,
            'section_id' => $section_id
        ])->when($type, function ($query) use ($type) {
            return $query->where('type', $type);
        })->pluck('punch_card_id')->filter(function($item) {
                return $item;
            });

        $hrAttendance =  HrAttendanceSummary::query()->whereDate('date', $date)
            ->with(['employeeOfficialInfo', 'employeeBasicInfo'])
            ->where('working_day_type', HrAttendanceSummary::REGULAR_WORKING_DAY)
            ->whereIn('userid', $user_ids)
            ->get();

        return $hrAttendance;

    }
}
