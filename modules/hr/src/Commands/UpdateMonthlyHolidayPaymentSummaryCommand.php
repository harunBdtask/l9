<?php

namespace SkylarkSoft\GoRMG\HR\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use DB, Exception;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Models\HrFastivalLeave;
use SkylarkSoft\GoRMG\HR\Models\HrHoliday;
use SkylarkSoft\GoRMG\HR\Models\HrHolidayAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Models\HrHolidayPaymentSummary;

class UpdateMonthlyHolidayPaymentSummaryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:monthly-holiday-payment-summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update HolidayPaymentSummary Model';

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
            $pay_month = $year . '-' . $month . '-01';

            HrEmployeeOfficialInfo::whereNotNull('unique_id')
                ->orderBy('unique_id')
                ->chunk(200, function ($employees, $key) use ($month, $year, $pay_month) {
                    foreach ($employees as $key => $employee) {
                        $this->info($employee->unique_id);
                        $data = $this->calculateMonthlyHolidayPaymentSummary($month, $year, $employee);
                        $data['pay_month'] = $pay_month;
                        $this->updateMonthlyHolidayPaymentSummary($data);
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
     * Update Monthly Holiday Payment Summary
     *
     * @param $payment_summary_data
     * @return bool
     */
    private function updateMonthlyHolidayPaymentSummary($payment_summary_data)
    {
        HrHolidayPaymentSummary::where([
            'pay_month' => $payment_summary_data['pay_month'],
            'userid' => $payment_summary_data['userid'],
        ])->delete();
        $monthly_payment_summary_data = $payment_summary_data->toArray();
        HrHolidayPaymentSummary::create($monthly_payment_summary_data);
        return true;
    }

    /**
     * Calculate Monthly Holiday Payment Summary
     *
     * @param $month
     * @param $year
     * @param $employee
     * @return array
     */
    private function calculateMonthlyHolidayPaymentSummary($month, $year, $employee)
    {
        $employee_id = $employee->employee_id;
        $userid = $employee->unique_id;
        $total_working_holiday = $this->calculateTotalWorkingHoliday($month, $year, $userid);
        $total_working_minute = $this->calculateTotalHolidayWorkingMinute($userid, $month, $year);
        $total_working_hour_time = $this->convertMinutesToTime($total_working_minute);
        $total_working_hour = round(($total_working_minute / 60), 3);
        $payment_rate = $this->calculatePaymentRate($employee);
        $total_payable_amount = $this->calculatePayableAmount($total_working_hour, $payment_rate, $employee);
        return collect([
            'userid' => $userid,
            'total_working_holiday' => $total_working_holiday,
            'total_working_hour_time' => $total_working_hour_time,
            'total_working_hour' => $total_working_hour,
            'total_working_minute' => $total_working_minute,
            'payment_rate' => $payment_rate,
            'total_payable_amount' => $total_payable_amount,
        ]);
    }

    /**
     * Calculate Total Working Holiday
     *
     * @param $month
     * @param $year
     * @param $userid
     * @return mixed
     */
    private function calculateTotalWorkingHoliday($month, $year, $userid)
    {
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth()->toDateString();
        $end_date = $date->copy()->endOfMonth()->toDateString();
        $fridays = $this->getFridaysInMonth($month, $year);
        $holidays = $this->getHolidaysInMonth($month, $year);
        $festival_days = $this->getFestivalDaysInMonth($month, $year);

        $all_leave_dates = array_merge($fridays, $holidays, $festival_days);

        return HrHolidayAttendanceSummary::whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date)
            ->whereIn('date', $all_leave_dates)
            ->where('userid', $userid)
            ->where('ot_eligible_status', HrHolidayAttendanceSummary::OT_ELIGIBLE)
            ->count();
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
     * Calculate Total Holiday Working Minute
     *
     * @param $userid
     * @param $month
     * @param $year
     * @return mixed
     */
    private function calculateTotalHolidayWorkingMinute($userid, $month, $year)
    {
        $date = Carbon::parse($year . '-' . $month . '-01');
        $start_date = $date->copy()->startOfMonth()->toDateString();
        $end_date = $date->copy()->endOfMonth()->toDateString();

        $fridays = $this->getFridaysInMonth($month, $year);
        $holidays = $this->getHolidaysInMonth($month, $year);
        $festival_days = $this->getFestivalDaysInMonth($month, $year);

        $all_leave_dates = array_merge($fridays, $holidays, $festival_days);

        return HrHolidayAttendanceSummary::whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date)
            ->whereIn('date', $all_leave_dates)
            ->where('userid', $userid)
            ->where('ot_eligible_status', HrHolidayAttendanceSummary::OT_ELIGIBLE)
            ->sum('total_approved_work_minute');
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
     * Calculate Payment Rate
     *
     * @param $employee
     * @return float|int
     */
    private function calculatePaymentRate($employee)
    {
        if ($employee->type == 'staff') {
            $gross_salary = $employee->salary->gross;
            if ($gross_salary > 10000) {
                return 250;
            } else {
                return 200;
            }
        }
        $basic_salary = $employee->salary->basic;
        return round((($basic_salary * 2) / 208), 3);
    }

    /**
     * Calculate Payable Amount
     *
     * @param $total_working_hour
     * @param $payment_rate
     * @param $employee
     * @return int
     */
    private function calculatePayableAmount($total_working_hour, $payment_rate, $employee)
    {
        if ($total_working_hour <= 0) {
            return 0;
        }
        if ($employee->type == 'staff') {
            return $payment_rate;
        }
        return ($total_working_hour * $payment_rate);
    }
}
