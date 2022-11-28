<?php

namespace SkylarkSoft\GoRMG\HR\Commands;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use DB, Exception;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Models\HrDisciplineDetail;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Models\HrFastivalLeave;
use SkylarkSoft\GoRMG\HR\Models\HrHoliday;
use SkylarkSoft\GoRMG\HR\Models\HrLeaveApplicationDetail;
use SkylarkSoft\GoRMG\HR\Models\HrMonthlyPaymentSummary;

class UpdateMonthlyPaymentSummaryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:monthly-payment-summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update MonthlyPaymentSummary Model';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->info('Start Execution');
            $previous_month = Carbon::now()->subMonth();
            $month = $previous_month->copy()->format('m');
            $year = $previous_month->copy()->format('Y');

            DB::beginTransaction();
            $pay_month = $year.'-'.$month.'-01';
            $total_working_day = $this->calculateWorkingDays($month, $year);
            $total_weekend = $this->calculateWeekendDays($month, $year);
            $total_festival_day = $this->calculateFestivalDays($month, $year);
            $total_other_holiday = $this->calculateOtherHolidays($month, $year);
            $total_holiday = $total_weekend + $total_festival_day + $total_other_holiday;

            HrEmployeeOfficialInfo::whereNotNull('unique_id')
                ->orderBy('unique_id')
                ->chunk(200, function ($employees, $key) use ($month, $year, $pay_month, $total_working_day, $total_weekend, $total_festival_day, $total_other_holiday, $total_holiday) {
                    foreach ($employees as $key => $employee) {
                        $this->info($employee->unique_id);
                        $data = $this->calculateMonthlyPaymentSummary($month, $year, $employee, $total_working_day, $total_holiday);
                        $data['pay_month'] = $pay_month;
                        $data['total_working_day'] = $total_working_day;
                        $data['total_weekend'] = $total_weekend;
                        $data['total_festival_day'] = $total_festival_day;
                        $data['total_other_holiday'] = $total_other_holiday;
                        $data['total_holiday'] = $total_holiday;
                        $this->updateMonthlyPaymentSummary($data);
                    }
                });
            DB::commit();
            $this->info('End Execution');
            $this->info('success');
        } catch (Exception $e) {
            DB::rollBack();
            $this->info($e->getMessage());
        }
    }

    /**
     * Monthly Payment Summary Update
     *
     * @param Collection $payment_summary_data
     * @return bool
     */
    private function updateMonthlyPaymentSummary(Collection $payment_summary_data)
    {
        $monthly_payment_summary_query = HrMonthlyPaymentSummary::where([
            'pay_month' => $payment_summary_data['pay_month'],
            'userid' => $payment_summary_data['userid'],
        ]);
        $entry_by = auth()->user()->id ?? null;
        $monthly_payment_summary_data = $payment_summary_data->toArray();
        $monthly_payment_summary = $monthly_payment_summary_query->first();
        if (!$monthly_payment_summary) {
            $monthly_payment_summary_data['generated_by'] = $entry_by;
            $monthly_payment_summary_data['created_by'] = $entry_by;
            HrMonthlyPaymentSummary::create($monthly_payment_summary_data);
        } else {
            $monthly_payment_summary_data['updated_by'] = $entry_by;
            $monthly_payment_summary_query->update($monthly_payment_summary_data);
        }
        return true;
    }

    /**
     * Calculate Monthly Payment Summary
     *
     * @param $month
     * @param $year
     * @param $employee
     * @param $total_working_day
     * @param $total_holiday
     * @return \Illuminate\Support\Collection
     */
    private function calculateMonthlyPaymentSummary($month, $year, $employee, $total_working_day, $total_holiday)
    {
        $employee_id = $employee->employee_id;
        $userid = $employee->unique_id;
        $employee_type = $employee->type;
        $pay_month = Carbon::parse($year . '-' . $month . '-01');
        $employee_joining_date = Carbon::parse($employee->date_of_joining);
        $total_present_day = $this->calculateEmployeePresentDays($employee_id, $userid, $month, $year);
        $total_leave = $this->calculateEmployeeLeave($employee_id, $month, $year);
        $leave_in_holiday = $this->calculateEmployeeLeaveInHoliday($employee_id, $month, $year);
        $total_absent_day = $total_working_day - $total_present_day - $total_leave + $leave_in_holiday;
        $total_payable_days = $total_present_day + $total_holiday + $total_leave - $leave_in_holiday;
        $total_late = $this->calculateEmployeeLateDays($employee_id, $userid, $month, $year);
        $basic_salary = optional($employee->salary)->basic ?? 0;
        $house_rent = optional($employee->salary)->house_rent;
        $medical_allowance = optional($employee->salary)->medical;
        $transport_allowance = optional($employee->salary)->transport;
        $food_allowance = optional($employee->salary)->food;
        $attendance_bonus = optional($employee->salary)->attendance_bonus ?? 0;
        $gross_salary = optional($employee->salary)->gross;

        $ot_minute = $this->calculateEmployeeRegularOtMinute($employee_id, $userid, $month, $year, $employee_type);
        $ot_hour_time = $this->convertMinutesToTime($ot_minute);
        $ot_hour = round(($ot_minute / 60), 3);
        $ot_rate = round((($basic_salary * 2) / 208), 3);
        $total_ot_amount = $ot_hour * $ot_rate;

        $total_regular_extra_ot_minute = $this->calculateEmployeeRegularExtraOtMinute($employee_id, $userid, $month, $year, $employee_type);
        $total_regular_extra_ot_hour_time = $this->convertMinutesToTime($total_regular_extra_ot_minute);
        $total_regular_extra_ot_hour = round(($total_regular_extra_ot_minute / 60), 3);

        $total_regular_unapproved_extra_ot_minute = $this->calculateEmployeeRegularUnapprovedExtraOtMinute($employee_id, $userid, $month, $year, $employee_type);
        $total_regular_unapproved_extra_ot_hour_time = $this->convertMinutesToTime($total_regular_unapproved_extra_ot_minute);
        $total_regular_unapproved_extra_ot_hour = round(($total_regular_unapproved_extra_ot_minute / 60), 3);

        $night_ot_minute = $this->calculateEmployeeNightOtMinute($employee_id, $userid, $month, $year);
        $night_ot_hour_time = $this->convertMinutesToTime($night_ot_minute);
        $night_ot_hour = round(($night_ot_minute / 60), 3);
        $night_ot_rate = $this->calculateNightOtRate($basic_salary, $employee);
        $night_ot_amount = $this->calculateNightOtAmount($night_ot_hour, $night_ot_rate, $employee);

        $total_night_unapproved_ot_minute = $this->calculateEmployeeNightUnapprovedOtMinute($employee_id, $userid, $month, $year);
        $total_night_unapproved_ot_hour_time = $this->convertMinutesToTime($total_night_unapproved_ot_minute);
        $total_night_unapproved_ot_hour = round(($total_night_unapproved_ot_minute / 60), 3);

        $absent_deduction = round((($basic_salary * $total_absent_day) / 30), 3);
        $attendance_bonus_deduction = $this->calculateAttendanceBonusDeduction($attendance_bonus, $total_leave, $total_absent_day, $employee_id, $userid, $month, $year);
        $revenue_stamp = 10;
        $disciplinary_deduction = (float)$this->calculateDisciplinaryDeduction($employee_id, $pay_month);
        if ($employee_type == 'staff') {
            $total_payable_amount = $gross_salary + $night_ot_amount - $revenue_stamp - $absent_deduction - $disciplinary_deduction;
        } else {
            $total_payable_amount = $gross_salary + $total_ot_amount + $attendance_bonus - $attendance_bonus_deduction - $revenue_stamp - $absent_deduction;
        }
        // For Joining date in this month
        if ($employee_joining_date->copy()->month == $pay_month->copy()->month && $employee_joining_date->copy()->year == $pay_month->copy()->year) {
            $after_joining_date = $employee_joining_date->copy()->toDateString();
            $total_working_day_after_joining = $this->calculateWorkingDays($month, $year, $after_joining_date);
            $total_absent_day = $total_working_day_after_joining - $total_present_day - $total_leave;
            $absent_deduction = round((($basic_salary * $total_absent_day) / 30), 3);
            $total_weekend = $this->calculateWeekendDays($month, $year, $after_joining_date);
            $total_festival_day = $this->calculateFestivalDays($month, $year, $after_joining_date);
            $total_other_holiday = $this->calculateOtherHolidays($month, $year, $after_joining_date);
            $total_holiday_after_joining_date = $total_weekend + $total_festival_day + $total_other_holiday;
            $total_payable_days = $total_present_day + $total_holiday_after_joining_date;
            $gross_salary_for_joining_month = $pay_month->copy()->daysInMonth > 0 ? (($gross_salary / $pay_month->copy()->daysInMonth) * $total_payable_days) : (($gross_salary / 30) * $total_payable_days);
            if ($employee_type == 'staff') {
                $total_payable_amount = $gross_salary_for_joining_month + $night_ot_amount - $revenue_stamp - $absent_deduction - $disciplinary_deduction;
            } else {
                $total_payable_amount = $gross_salary_for_joining_month + $total_ot_amount + $attendance_bonus - $attendance_bonus_deduction - $revenue_stamp - $absent_deduction;
            }
        }
        if ($total_payable_amount < 0) {
            $total_payable_amount = 0;
        }
        $pay_slip_generate_date = date('Y-m-d');

        return collect([
            'userid' => $userid,
            'total_present_day' => $total_present_day,
            'total_leave' => $total_leave,
            'total_absent_day' => $total_absent_day,
            'total_late' => $total_late,
            'total_payable_days' => $total_payable_days,
            'basic_salary' => $basic_salary,
            'house_rent' => $house_rent,
            'medical_allowance' => $medical_allowance,
            'transport_allowance' => $transport_allowance,
            'food_allowance' => $food_allowance,
            'attendance_bonus' => $attendance_bonus,
            'gross_salary' => $gross_salary,
            'ot_minute' => $ot_minute,
            'ot_hour_time' => $ot_hour_time,
            'ot_hour' => $ot_hour,
            'ot_rate' => $ot_rate,
            'total_ot_amount' => $total_ot_amount,
            'total_regular_extra_ot_hour_time' => $total_regular_extra_ot_hour_time,
            'total_regular_extra_ot_hour' => $total_regular_extra_ot_hour,
            'total_regular_extra_ot_minute' => $total_regular_extra_ot_minute,
            'total_regular_unapproved_extra_ot_hour_time' => $total_regular_unapproved_extra_ot_hour_time,
            'total_regular_unapproved_extra_ot_hour' => $total_regular_unapproved_extra_ot_hour,
            'total_regular_unapproved_extra_ot_minute' => $total_regular_unapproved_extra_ot_minute,
            'absent_deduction' => $absent_deduction,
            'attendance_bonus_deduction' => $attendance_bonus_deduction,
            'revenue_stamp' => $revenue_stamp,
            'total_payable_amount' => $total_payable_amount,
            'pay_slip_generate_date' => $pay_slip_generate_date,
            'night_ot_hour_time' => $night_ot_hour_time,
            'night_ot_hour' => $night_ot_hour,
            'night_ot_minute' => $night_ot_minute,
            'night_ot_rate' => $night_ot_rate,
            'night_ot_amount' => $night_ot_amount,
            'total_night_unapproved_ot_hour_time' => $total_night_unapproved_ot_hour_time,
            'total_night_unapproved_ot_hour' => $total_night_unapproved_ot_hour,
            'total_night_unapproved_ot_minute' => $total_night_unapproved_ot_minute
        ]);
    }

    /**
     * Calculate Month Wise Working Days
     *
     * @param $month
     * @param $year
     * @return int
     */
    private function calculateWorkingDays($month, $year, $after_this_date = '')
    {
        $working_days = 0;
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth();
        if ($after_this_date != '') {
            $date = Carbon::parse($after_this_date);
            $start_date = $date->copy();
        }
        $end_date = $date->copy()->endOfMonth();
        $working_days += $start_date->diffFiltered(CarbonInterval::day(), function (Carbon $date) {
            $isHoliday = HrHoliday::whereDate('date', $date->copy()->toDateString())->first();
            $isFestival = HrFastivalLeave::whereDate('leave_date', $date->copy()->toDateString())->first();
            return !$date->isFriday() && !$isHoliday && !$isFestival;
        }, $end_date);
        return $working_days;
    }

    /**
     * Calculate Month Wise Weekend Days
     *
     * @param $month
     * @param $year
     * @return int
     */
    private function calculateWeekendDays($month, $year, $after_this_date = '')
    {
        $weekend_days = 0;
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth();
        if ($after_this_date != '') {
            $date = Carbon::parse($after_this_date);
            $start_date = $date->copy();
        }
        $end_date = $date->copy()->endOfMonth();
        $weekend_days += $start_date->diffFiltered(CarbonInterval::day(), function (Carbon $date) {
            return $date->isFriday();
        }, $end_date);
        return $weekend_days;
    }

    /**
     * Calculate Month Wise Festival Days
     *
     * @param $month
     * @param $year
     * @return int
     */
    private function calculateFestivalDays($month, $year, $after_this_date = '')
    {
        $festival_days = 0;
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth();
        if ($after_this_date != '') {
            $date = Carbon::parse($after_this_date);
            $start_date = $date->copy();
        }
        $end_date = $date->copy()->endOfMonth();
        $festival_days += $start_date->diffFiltered(CarbonInterval::day(), function (Carbon $date) {
            $isFestival = HrFastivalLeave::whereDate('leave_date', $date->copy()->toDateString())->first();
            return $isFestival;
        }, $end_date);
        return $festival_days;
    }

    /**
     * Calculate Month Wise Holidays
     *
     * @param $month
     * @param $year
     * @return int
     */
    private function calculateOtherHolidays($month, $year, $after_this_date = '')
    {
        $holidays = 0;
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth();
        if ($after_this_date != '') {
            $date = Carbon::parse($after_this_date);
            $start_date = $date->copy();
        }
        $end_date = $date->copy()->endOfMonth();
        $holidays += $start_date->diffFiltered(CarbonInterval::day(), function (Carbon $date) {
            $isHoliday = HrHoliday::whereDate('date', $date->copy()->toDateString())->first();
            return $isHoliday;
        }, $end_date);
        return $holidays;
    }

    /**
     * Calculate Employee Leave Days
     *
     * @param $employee_id
     * @param $month
     * @param $year
     * @return mixed
     */
    private function calculateEmployeeLeave($employee_id, $month, $year)
    {
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth()->toDateString();
        $end_date = $date->copy()->endOfMonth()->toDateString();
        $leave = HrLeaveApplicationDetail::whereDate('leave_date', '>=', $start_date)
            ->whereDate('leave_date', '<=', $end_date)
            ->where('employee_id', $employee_id)
            ->get()
            ->where('leave.is_approved', 'yes')
            ->groupBy('leave_date')
            ->count();
        return $leave;
    }

    /**
     * Calculate Employee Leave In Holiday
     *
     * @param $employee_id
     * @param $month
     * @param $year
     * @return int
     */
    private function calculateEmployeeLeaveInHoliday($employee_id, $month, $year)
    {
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth()->toDateString();
        $end_date = $date->copy()->endOfMonth()->toDateString();
        $leave = 0;
        HrLeaveApplicationDetail::whereDate('leave_date', '>=', $start_date)
            ->whereDate('leave_date', '<=', $end_date)
            ->where('employee_id', $employee_id)
            ->get()
            ->where('leave.is_approved', 'yes')
            ->groupBy('leave_date')
            ->each(function ($item, $key) use (&$leave) {
                $leave_date = Carbon::parse($item->first()->leave_date);
                $isHoliday = HrHoliday::where('date', $leave_date->copy()->toDateString())->first();
                $isFestival = HrFastivalLeave::where('leave_date', $leave_date->copy()->toDateString())->first();
                if ($leave_date->copy()->isFriday()) {
                    $leave += 1;
                } elseif ($isHoliday) {
                    $leave += 1;
                } elseif ($isFestival) {
                    $leave += 1;
                } else {
                    $leave += 0;
                }
            });
        return $leave;
    }

    /**
     * Calculate Employee Present Days
     *
     * @param $employee_id
     * @param $userid
     * @param $month
     * @param $year
     * @return int
     */
    private function calculateEmployeePresentDays($employee_id, $userid, $month, $year)
    {
        $present_days = 0;
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth()->toDateString();
        $end_date = $date->copy()->endOfMonth()->toDateString();
        $employee_leave_dates = $this->getEmployeeLeaveDatesInMonth($employee_id, $month, $year);
        $fridays = $this->getFridaysInMonth($month, $year);
        $holidays = $this->getHolidaysInMonth($month, $year);
        $festival_days = $this->getFestivalDaysInMonth($month, $year);

        $all_leave_dates = array_merge($fridays, $holidays, $festival_days, $employee_leave_dates);
        $present_days = HrAttendanceSummary::select('date')
            ->whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date)
            ->whereNotIn('date', $all_leave_dates)
            ->where('present_status', 1)
            ->where('working_day_type', 1)
            ->where('userid', $userid)
            ->groupBy('date')
            ->get()
            ->count();
        return $present_days;
    }

    /**
     * Calculate Employee Late Days
     *
     * @param $employee_id
     * @param $userid
     * @param $month
     * @param $year
     * @return int
     */
    private function calculateEmployeeLateDays($employee_id, $userid, $month, $year)
    {
        $late_days = 0;
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth()->toDateString();
        $end_date = $date->copy()->endOfMonth()->toDateString();
        $employee_leave_dates = $this->getEmployeeLeaveDatesInMonth($employee_id, $month, $year);
        $fridays = $this->getFridaysInMonth($month, $year);
        $holidays = $this->getHolidaysInMonth($month, $year);
        $festival_days = $this->getFestivalDaysInMonth($month, $year);

        $all_leave_dates = array_merge($fridays, $holidays, $festival_days, $employee_leave_dates);
        $late_days = HrAttendanceSummary::select('date')
            ->whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date)
            ->whereNotIn('date', $all_leave_dates)
            ->where('status', 'late')
            ->where('userid', $userid)
            ->where('working_day_type', 1)
            ->groupBy('date')
            ->get()
            ->count();
        return $late_days;
    }

    /**
     * All Fridays in a month
     *
     * @param $month
     * @param $year
     * @return array
     */
    private function getFridaysInMonth($month, $year)
    {
        $fridays = [];
        $mkdate = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $mkdate->copy()->startOfMonth()->isFriday() ? $mkdate->copy()->startOfMonth() : $mkdate->copy()->startOfMonth()->next(Carbon::FRIDAY);
        $end_date = $mkdate->copy()->endOfMonth();

        for ($date = $start_date; $date->lte($end_date); $date->addWeek()) {
            $fridays[] = $date->format('Y-m-d');
        }
        return $fridays;
    }

    /**
     * Month Wise Holiday Dates
     *
     * @param $month
     * @param $year
     * @return mixed
     */
    private function getHolidaysInMonth($month, $year)
    {
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth()->toDateString();
        $end_date = $date->copy()->endOfMonth()->toDateString();

        return HrHoliday::whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date)
            ->pluck('date')->toArray();
    }

    /**
     * Month wise Festival Leave Dates
     *
     * @param $month
     * @param $year
     * @return mixed
     */
    private function getFestivalDaysInMonth($month, $year)
    {
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth()->toDateString();
        $end_date = $date->copy()->endOfMonth()->toDateString();

        return HrFastivalLeave::whereDate('leave_date', '>=', $start_date)
            ->whereDate('leave_date', '<=', $end_date)
            ->pluck('leave_date')->toArray();
    }

    /**
     * Month wise Employee Leave Dates
     *
     * @param $employee_id
     * @param $month
     * @param $year
     * @return mixed
     */
    private function getEmployeeLeaveDatesInMonth($employee_id, $month, $year)
    {
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth()->toDateString();
        $end_date = $date->copy()->endOfMonth()->toDateString();
        $leave_dates = HrLeaveApplicationDetail::whereDate('leave_date', '>=', $start_date)
            ->whereDate('leave_date', '<=', $end_date)
            ->where('employee_id', $employee_id)
            ->get()
            ->where('leave.is_approved', 'yes')
            ->pluck('leave_date')->toArray();
        return $leave_dates;
    }

    /**
     * Calculate Employee Regular OT minutes
     *
     * @param $employee_id
     * @param $userid
     * @param $month
     * @param $year
     * @return mixed
     */
    private function calculateEmployeeRegularOtMinute($employee_id, $userid, $month, $year, $employee_type)
    {
        if ($employee_type == 'staff') {
            return 0;
        }
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth()->toDateString();
        $end_date = $date->copy()->endOfMonth()->toDateString();

        $employee_leave_dates = $this->getEmployeeLeaveDatesInMonth($employee_id, $month, $year);
        $fridays = $this->getFridaysInMonth($month, $year);
        $holidays = $this->getHolidaysInMonth($month, $year);
        $festival_days = $this->getFestivalDaysInMonth($month, $year);

        $all_leave_dates = array_merge($fridays, $holidays, $festival_days, $employee_leave_dates);

        return HrAttendanceSummary::whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date)
            ->whereNotIn('date', $all_leave_dates)
            ->where([
                'ot_eligible_status' => 1,
                'present_status' => 1,
                'userid' => $userid
            ])->sum('regular_ot_minute');
    }

    /**
     * Calculate Employee Regular Extra Ot Minute
     *
     * @param $employee_id
     * @param $userid
     * @param $month
     * @param $year
     * @return mixed
     */
    private function calculateEmployeeRegularExtraOtMinute($employee_id, $userid, $month, $year, $employee_type)
    {
        if ($employee_type == 'staff') {
            return 0;
        }
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth()->toDateString();
        $end_date = $date->copy()->endOfMonth()->toDateString();

        $employee_leave_dates = $this->getEmployeeLeaveDatesInMonth($employee_id, $month, $year);
        $fridays = $this->getFridaysInMonth($month, $year);
        $holidays = $this->getHolidaysInMonth($month, $year);
        $festival_days = $this->getFestivalDaysInMonth($month, $year);

        $all_leave_dates = array_merge($fridays, $holidays, $festival_days, $employee_leave_dates);

        return HrAttendanceSummary::whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date)
            ->whereNotIn('date', $all_leave_dates)
            ->where([
                'ot_eligible_status' => 1,
                'present_status' => 1,
                'userid' => $userid
            ])->sum('extra_ot_minute_same_day');
    }

    /**
     * Calculate Employee Regular Unapproved Extra Ot Minute
     *
     * @param $employee_id
     * @param $userid
     * @param $month
     * @param $year
     * @return mixed
     */
    private function calculateEmployeeRegularUnapprovedExtraOtMinute($employee_id, $userid, $month, $year, $employee_type)
    {
        if ($employee_type == 'staff') {
            return 0;
        }
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth()->toDateString();
        $end_date = $date->copy()->endOfMonth()->toDateString();

        $employee_leave_dates = $this->getEmployeeLeaveDatesInMonth($employee_id, $month, $year);
        $fridays = $this->getFridaysInMonth($month, $year);
        $holidays = $this->getHolidaysInMonth($month, $year);
        $festival_days = $this->getFestivalDaysInMonth($month, $year);

        $all_leave_dates = array_merge($fridays, $holidays, $festival_days, $employee_leave_dates);

        return HrAttendanceSummary::whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date)
            ->whereNotIn('date', $all_leave_dates)
            ->where([
                'ot_eligible_status' => 1,
                'present_status' => 1,
                'userid' => $userid
            ])->sum('unapproved_ot_minute');
    }

    /**
     * Attendance Deduction Calculation
     *
     * @param $attendance_bonus
     * @param $total_leave
     * @param $total_absent_day
     * @param $employee_id
     * @param $userid
     * @param $month
     * @param $year
     * @return float
     */
    private function calculateAttendanceBonusDeduction($attendance_bonus, $total_leave, $total_absent_day, $employee_id, $userid, $month, $year)
    {
        if ($total_leave > 0) {
            return $attendance_bonus;
        } elseif ($total_absent_day > 0) {
            return $attendance_bonus;
        } else {
            $date = Carbon::parse($year . '-' . $month . '-01');
            $start_date = $date->copy()->startOfMonth()->toDateString();
            $end_date = $date->copy()->endOfMonth()->toDateString();

            $fridays = $this->getFridaysInMonth($month, $year);
            $holidays = $this->getHolidaysInMonth($month, $year);
            $festival_days = $this->getFestivalDaysInMonth($month, $year);

            $all_holiday_dates = array_merge($fridays, $holidays, $festival_days);

            $user_late_count = HrAttendanceSummary::whereDate('date', '>=', $start_date)
                ->whereDate('date', '<=', $end_date)
                ->whereNotIn('date', $all_holiday_dates)
                ->where([
                    'status' => 'late',
                    'userid' => $userid
                ])->count();

            if ($user_late_count >= 3) {
                return $attendance_bonus;
            } elseif ($user_late_count < 3 && $user_late_count > 1) {
                return round(($attendance_bonus - ($attendance_bonus / 4)), 3);
            } elseif ($user_late_count == 1) {
                return round(($attendance_bonus - (($attendance_bonus * 2) / 3)), 3);
            } else {
                return 0;
            }
        }

    }

    /**
     * Convert Minutes to HH:MM:SS format
     *
     * @param $minutes
     * @return string
     */
    public function convertMinutesToTime($minutes)
    {
        // start by converting to seconds
        $seconds = floor($minutes * 60);
        // we're given minutes, so let's get those the easy way
        $hours = floor($minutes / 60);
        // since we've "calculated" hours, let's remove them from the seconds variable
        $seconds -= floor($hours * 3600);
        // calculate minutes left
        $minutes = floor($seconds / 60);
        // remove those from seconds as well
        $seconds -= floor($minutes * 60);
        // return the time formatted HH:MM:SS
        return str_pad($hours, 2, 0, STR_PAD_LEFT) . ":" . str_pad($minutes, 2, 0, STR_PAD_LEFT) . ":" . str_pad($seconds, 2, 0, STR_PAD_LEFT);
    }

    /**
     * Calculate Employee Night OT Minute
     *
     * @param $employee_id
     * @param $userid
     * @param $month
     * @param $year
     * @return mixed
     */
    private function calculateEmployeeNightOtMinute($employee_id, $userid, $month, $year)
    {
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth()->toDateString();
        $end_date = $date->copy()->endOfMonth()->toDateString();

        $employee_leave_dates = $this->getEmployeeLeaveDatesInMonth($employee_id, $month, $year);
        $fridays = $this->getFridaysInMonth($month, $year);
        $holidays = $this->getHolidaysInMonth($month, $year);
        $festival_days = $this->getFestivalDaysInMonth($month, $year);

        $all_leave_dates = array_merge($fridays, $holidays, $festival_days, $employee_leave_dates);

        return HrAttendanceSummary::whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date)
            ->whereNotIn('date', $all_leave_dates)
            ->where([
                'night_ot_eligible_status' => 1,
                'present_status' => 1,
                'userid' => $userid
            ])->sum('total_night_ot_minute');
    }

    /**
     * Calculate Employee Night Unapproved Ot Minute
     *
     * @param $employee_id
     * @param $userid
     * @param $month
     * @param $year
     * @return mixed
     */
    private function calculateEmployeeNightUnapprovedOtMinute($employee_id, $userid, $month, $year)
    {
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth()->toDateString();
        $end_date = $date->copy()->endOfMonth()->toDateString();

        $employee_leave_dates = $this->getEmployeeLeaveDatesInMonth($employee_id, $month, $year);
        $fridays = $this->getFridaysInMonth($month, $year);
        $holidays = $this->getHolidaysInMonth($month, $year);
        $festival_days = $this->getFestivalDaysInMonth($month, $year);

        $all_leave_dates = array_merge($fridays, $holidays, $festival_days, $employee_leave_dates);

        return HrAttendanceSummary::whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date)
            ->whereNotIn('date', $all_leave_dates)
            ->where([
                'night_ot_eligible_status' => 1,
                'present_status' => 1,
                'userid' => $userid
            ])->sum('unapproved_night_ot_minute');
    }

    /**
     * Calculate Night OT Rate
     *
     * @param $basic_salary
     * @param $employee
     * @return float|int
     */
    private function calculateNightOtRate($basic_salary, $employee)
    {
        if ($employee->type == 'staff') {
            return 100;
        }
        return round((($basic_salary * 2) / 208), 3);
    }

    /**
     * Calculate Night OT Amount
     *
     * @param $night_ot_hour
     * @param $night_ot_rate
     * @param $employee
     * @return int
     */
    private function calculateNightOtAmount($night_ot_hour, $night_ot_rate, $employee)
    {
        if ($night_ot_hour > 0 && $employee->type == 'staff') {
            return $night_ot_rate;
        } elseif ($night_ot_hour <= 0 && $employee->type == 'staff') {
            return 0;
        }
        return ($night_ot_hour * $night_ot_rate) > 0 ? (($night_ot_hour * $night_ot_rate) + 30) : 0;
    }

    /**
     * Calculate Disciplinary Deduction Amount
     *
     * @param $employee_id
     * @param Carbon $pay_month
     * @return mixed
     */
    private function calculateDisciplinaryDeduction($employee_id, Carbon $pay_month)
    {
        return HrDisciplineDetail::where('employee_id', $employee_id)
            ->whereMonth('deduction_month', $pay_month->copy()->month)
            ->whereYear('deduction_month', $pay_month->copy()->year)
            ->sum('amount');
    }
}
