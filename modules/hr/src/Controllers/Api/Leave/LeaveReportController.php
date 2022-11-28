<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Leave;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Models\HrLeaveApplicationDetail;

class LeaveReportController
{
    public function individualLeaveReport(Request $request)
    {
        $employee_id = $request->employee_id ?? null;
        $from_date = $request->from_date ?? null;
        $to_date = $request->to_date ?? null;
        $reports = $this->getIndividualLeaveReport($employee_id, $from_date, $to_date);
        $view = view('hr::reports.individual_leave_report', [
            'reports' => $reports,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ])->render();
        return response()->json([
            'status' => 'success',
            'view' => $view
        ]);
    }

    private function getIndividualLeaveReport($employee_id = '', $from_date = '', $to_date = '')
    {
        if ($employee_id == '' || $from_date == '' || $to_date == '') {
            return null;
        }
        return HrLeaveApplicationDetail::where('employee_id', $employee_id)
            ->whereDate('leave_date', '>=', $from_date)
            ->whereDate('leave_date', '<=', $to_date)
            ->orderBy('leave_date', 'desc')
            ->get();
    }

    public function yearlyLeaveReport(Request $request)
    {
        $department_id = $request->department_id ?? null;
        $section_id = $request->section_id ?? null;
        $year = $request->year ?? null;
        $type = $request->type ?? null;
        $reports = $this->getYearlyLeaveReport($department_id, $section_id, $year, $type);
        $view = view('hr::reports.yearly_leave_report', [
            'reports' => $reports,
            'year' => $year,
        ])->render();

        return response()->json([
            'status' => 'success',
            'view' => $view
        ]);
    }

    private function getYearlyLeaveReport($department_id = '', $section_id = '', $year = '', $type = '')
    {
        if ($department_id == '' || $section_id == '' || $year == '') {
            return null;
        }
        $from_date = Carbon::parse('first day of January ' . $year)->toDateString();
        $to_date = Carbon::parse('last day of December ' . $year)->toDateString();
        $employee_ids = HrEmployeeOfficialInfo::where([
            'department_id' => $department_id,
            'section_id' => $section_id,
        ])->when($type != '', function ($query) use ($type) {
            return $query->where('type', $type);
        })->pluck('employee_id')->toArray();
        return HrLeaveApplicationDetail::with('employeeOfficialInfo')
            ->selectRaw("employee_id, COUNT(*) as total_leave")
            ->whereIn('employee_id', $employee_ids)
            ->whereDate('leave_date', '>=', $from_date)
            ->whereDate('leave_date', '<=', $to_date)
            ->groupBy('employee_id')
            ->get();
    }

    public function monthlyLeaveReport(Request $request)
    {
        $department_id = $request->department_id ?? null;
        $section_id = $request->section_id ?? null;
        $month = $request->month ?? null;
        $year = $request->year ?? null;
        $type = $request->type ?? null;
        $from_date = null;
        $to_date = null;
        if ($month && $year) {
            $date = Carbon::parse($year . '-' . $month . '-01');
            $from_date = $date->copy()->startOfMonth()->toDateString();
            $to_date = $date->copy()->endOfMonth()->toDateString();
        }
        $reports = $this->getMonthlyLeaveReport($department_id, $section_id, $month, $year, $type);
        $view = view('hr::reports.monthly_leave_report', [
            'reports' => $reports,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ])->render();
        return response()->json([
            'status' => 'success',
            'view' => $view
        ]);
    }

    private function getMonthlyLeaveReport($department_id = '', $section_id = '', $month = '', $year = '', $type = '')
    {
        if ($department_id == '' || $section_id == '' || $year == '' || $month == '') {
            return null;
        }
        $date = Carbon::parse($year . '-' . $month . '-01');
        $from_date = $date->copy()->startOfMonth()->toDateString();
        $to_date = $date->copy()->endOfMonth()->toDateString();
        $employee_ids = HrEmployeeOfficialInfo::where([
            'department_id' => $department_id,
            'section_id' => $section_id,
        ])->when($type != '', function ($query) use ($type) {
            return $query->where('type', $type);
        })->pluck('employee_id')->toArray();
        return HrLeaveApplicationDetail::with('employeeOfficialInfo', 'type')
            ->selectRaw("employee_id, type_id, COUNT(*) as total_leave_days")
            ->whereIn('employee_id', $employee_ids)
            ->whereDate('leave_date', '>=', $from_date)
            ->whereDate('leave_date', '<=', $to_date)
            ->groupBy('employee_id', 'type_id')
            ->get();
    }
}
