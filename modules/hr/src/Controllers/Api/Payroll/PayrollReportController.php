<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Payroll;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Models\HrHolidayPaymentSummary;
use SkylarkSoft\GoRMG\HR\Models\HrMonthlyPaymentSummary;

class PayrollReportController
{
    public function employeesMonthlyPaySheet(Request $request)
    {
        $month = $request->month ?? null;
        $year = $request->year ?? null;
        $department_id = $request->department_id ?? null;
        $section_id = $request->section_id ?? null;
        $type = $request->type ?? null;
        $pay_month = ($month & $year) ? $year.'-'.$month.'-01' : null;

        $reports = $this->employeesMonthlyPaySheetData($month, $year, $department_id, $section_id, $type);

        if (isset($type) && $type == 'staff') {
            return view('hr::reports.monthly_pay_sheet_report_for_staff', [
                'reports' => $reports,
                'month' => $month,
                'year' => $year,
                'pay_month' => $pay_month
            ])->render();
        }
        return view('hr::reports.monthly_pay_sheet_report', [
            'reports' => $reports,
            'month' => $month,
            'year' => $year,
            'pay_month' => $pay_month
        ])->render();
    }

    private function employeesMonthlyPaySheetData($month = '', $year = '', $department_id = '', $section_id = '', $type = '')
    {
        if ($month == '' || $year == '' || $department_id == '' || $section_id == '') {
            return null;
        }
        $pay_month = $year.'-'.$month.'-01';
        $user_ids = HrEmployeeOfficialInfo::where([
            'department_id' => $department_id,
            'section_id' => $section_id,
            'type' => 'worker'
        ])->pluck('unique_id')->toArray();

        return HrMonthlyPaymentSummary::whereDate('pay_month', $pay_month)->whereIn('userid', $user_ids)->get();
    }

    public function employeesMonthlyExtraOtSheet(Request $request)
    {
        $month = $request->month ?? null;
        $year = $request->year ?? null;
        $department_id = $request->department_id ?? null;
        $section_id = $request->section_id ?? null;
        $type = $request->type ?? null;
        $pay_month = ($month & $year) ? $year.'-'.$month.'-01' : null;

        $reports = $this->employeesMonthlyPaySheetData($month, $year, $department_id, $section_id, $type);

        return view('hr::reports.monthly_extra_ot_sheet_report', [
            'reports' => $reports,
            'month' => $month,
            'year' => $year,
            'pay_month' => $pay_month
        ])->render();
    }

    public function employeesMonthlyHolidayOtSheet(Request $request)
    {
        $month = $request->month ?? null;
        $year = $request->year ?? null;
        $department_id = $request->department_id ?? null;
        $section_id = $request->section_id ?? null;
        $type = $request->type ?? null;
        $pay_month = ($month & $year) ? $year.'-'.$month.'-01' : null;

        $reports = $this->employeesMonthlyHolidayPaySheetData($month, $year, $department_id, $section_id, $type);

        return view('hr::reports.monthly_holiday_ot_sheet_report', [
            'reports' => $reports,
            'month' => $month,
            'year' => $year,
            'pay_month' => $pay_month
        ])->render();
    }

    private function employeesMonthlyHolidayPaySheetData($month = '', $year = '', $department_id = '', $section_id = '', $type = '')
    {
        if ($month == '' || $year == '' || $department_id == '' || $section_id == '') {
            return null;
        }
        $pay_month = $year.'-'.$month.'-01';
        $user_ids = HrEmployeeOfficialInfo::where([
            'department_id' => $department_id,
            'section_id' => $section_id
        ])->when($type != '', function ($query) use ($type) {
            return $query->where('type', $type);
        })->pluck('unique_id')->toArray();

        return HrHolidayPaymentSummary::whereDate('pay_month', $pay_month)->whereIn('userid', $user_ids)->get();
    }

    public function totalPaymentSummary(Request $request)
    {
        $month = $request->month ?? Carbon::now()->subMonth()->format('m');
        $year = $request->year ?? Carbon::now()->subMonth()->format('Y');
        $data['date'] = $request->year.'-'.$request->month.'-01';
        $data['regular_reports_data'] = HrMonthlyPaymentSummary::whereMonth('pay_month', $month)->whereYear('pay_month', $year)->get()->map(function ($item, $key) {
            $item->extra_ot_amount = $item->ot_rate * $item->total_regular_extra_ot_hour;
            return $item;
        });
        return view('hr::reports.total_payment_summary', $data);
    }
}
