<?php

namespace SkylarkSoft\GoRMG\HR\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use DB, Exception;
use SkylarkSoft\GoRMG\HR\Helpers\CalculateAttendanceSummaryForShiftingEmployees;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Models\HrDailyRoasting;

class DailyGenerateAttendanceSummaryForShiftingEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:daily-attendance-summary-for-shifting-employees';

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
            $date = Carbon::now()->subDay();
            $this->info('Start General Attendance');
            $this->processAttendanceSummary($date);
            $this->info('End execution');
            DB::commit();
            $this->info('success');
        } catch (Exception $e) {
            DB::rollBack();
            $this->info($e->getMessage());
        }
    }

    private function processAttendanceSummary(Carbon $date)
    {
        $date = $date->copy()->toDateString();

        return HrDailyRoasting::whereDate('date', $date)
            ->orderBy('date')
            ->orderBy('employee_id')
            ->chunk(500, function ($daily_roastings, $key) {
                foreach ($daily_roastings as $daily_roasting) {
                    $this->info('Date = '. $daily_roasting->date);
                    $this->info('User Id = '. $daily_roasting->employeeOfficialInfo->unique_id);
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
