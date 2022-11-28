<?php

namespace SkylarkSoft\GoRMG\HR\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use DB, Exception;
use SkylarkSoft\GoRMG\HR\Helpers\CalculateHolidayAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceRawData;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Models\HrHolidayAttendanceSummary;

class GenerateHolidayAttendanceSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:holiday-attendance-summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Holiday Attendance Summary';

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
            $date = Carbon::today()->subDay();
            $this->info('Execution Date= ' . $date);
            $checkIfDateHoliday = $this->checkIfDateIsHoliday($date);
            if (!$checkIfDateHoliday) {
                $this->info('Previous Day is not a holiday!');
            } else {
                $this->updateHolidayAttendanceData($date);
            }
            $this->info('End execution');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->info($e->getMessage());
            $this->info($e->getLine());
        }
    }

    /**
     * Update Holiday Attendance Data
     *
     * @param $date
     * @param string $userid
     * @return mixed
     */
    private function updateHolidayAttendanceData($date, $userid = '')
    {
        $userid = ($userid != '') ? $userid : null;
        $counter = 0;
        $userids_of_shifting_duty = HrEmployeeOfficialInfo::where([
            'department_id' => 7, //Personnel
            'section_id' => 18, //Security
            'designation_id' => 48 //Security
        ])->pluck('unique_id')->toArray();
        $process = HrAttendanceRawData::selectRaw("userid,
                    attendance_date,
                    MIN(punch_time) AS new_intime,
                    MAX(punch_time) AS new_outtime")
            ->where('attendance_date', $date)
            ->whereNotIn('userid', $userids_of_shifting_duty)
            ->when($userid != null, function ($query) use ($userid) {
                return $query->where('userid', $userid);
            })
            ->groupBy('userid', 'attendance_date')
            ->orderBy('attendance_date')
            ->orderBy('userid')
            ->chunk(200, function ($attendance_raw_datas, $key) use(&$counter) {
                foreach ($attendance_raw_datas as $attendance_raw_data) {
                    ++$counter;
                    $this->info('Start of userid ' . $attendance_raw_data->userid);
                    $holidayAttendanceDetails = (new CalculateHolidayAttendanceSummary($attendance_raw_data))->handle();
                    HrHolidayAttendanceSummary::where([
                        'userid' => $holidayAttendanceDetails['userid'],
                        'date' => $holidayAttendanceDetails['date']
                    ])->delete();
                    HrHolidayAttendanceSummary::create($holidayAttendanceDetails);
                    $this->info('End of userid ' . $attendance_raw_data->userid);
                }
            });
        $this->info('Total rows ' . $counter);
        return $process;
    }

    /**
     * Check If Date Is Holiday
     *
     * @param Carbon $date
     * @return bool
     */
    private function checkIfDateIsHoliday(Carbon $date)
    {
        $check = false;
        if ($date->copy()->isFriday()) {
            $check = true;
        }
        if (DB::table('hr_fastival_leaves')->whereDate('leave_date', $date->copy()->toDateString())->count()) {
            $check = true;
        }
        if (DB::table('hr_holidays')->whereDate('date', $date->copy()->toDateString())->count()) {
            $check = true;
        }
        return $check;
    }
}
