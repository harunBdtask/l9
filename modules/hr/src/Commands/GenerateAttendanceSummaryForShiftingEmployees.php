<?php

namespace SkylarkSoft\GoRMG\HR\Commands;

use Illuminate\Console\Command;
use DB, Exception;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\HR\Helpers\CalculateAttendanceSummaryForShiftingEmployees;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Models\HrDailyRoasting;

class GenerateAttendanceSummaryForShiftingEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:attendance-summary-for-shifting-employees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Attendance Summary For Shifting Employees';

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
            DB::beginTransaction();
            $this->info('Start execution');
            $date_from = Carbon::now()->subMonth()->startOfMonth();
            $date_to = Carbon::now()->subMonth()->endOfMonth();
            $this->info('Start General Attendance');
            $this->processAttendanceSummary($date_from, $date_to);
            $this->info('End execution');
            DB::commit();
            $this->info('success');
        } catch (Exception $e) {
            DB::rollBack();
            $this->info($e->getMessage());
        }
    }

    private function processAttendanceSummary(Carbon $from_date, Carbon $to_date)
    {
        $from_date = $from_date->copy()->toDateString();
        $to_date = $to_date->copy()->toDateString();

        return HrDailyRoasting::where('date', '>=', $from_date)
            ->where('date', '<=', $to_date)
            ->orderBy('date')
            ->orderBy('employee_id')
            ->chunk(500, function ($daily_roastings, $key) {
                foreach ($daily_roastings as $daily_roasting) {
                    $this->info('Date = ' . $daily_roasting->date);
                    $this->info('User Id = ' . $daily_roasting->employeeOfficialInfo->unique_id);
                    $date = $daily_roasting->date;
                    $attendanceDetails = (new CalculateAttendanceSummaryForShiftingEmployees($daily_roasting, $date))->handle();

                    HrAttendanceSummary::where([
                        'userid' => $attendanceDetails['userid'],
                        'date' => $attendanceDetails['date']
                    ])->delete();
                    HrAttendanceSummary::create($attendanceDetails);
                }
            });
    }
}
