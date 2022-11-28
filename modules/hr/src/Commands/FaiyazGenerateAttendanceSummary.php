<?php

namespace SkylarkSoft\GoRMG\HR\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use DB, Exception;
use SkylarkSoft\GoRMG\HR\Helpers\CalculateAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Helpers\CalculateAttendanceSummaryForNightOt;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceRawData;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;

class FaiyazGenerateAttendanceSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'faiyaz:generate-attendance-summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->generateAttendance();
    }

    public function generateAttendance()
    {

        try {
            $this->info('Start execution');
            DB::beginTransaction();
            $date = (Carbon::now()->subDays(1)->isFriday() ? Carbon::now()->subDays(2)->toDateString() : Carbon::now()->subDays(1)->toDateString());
            $punchAfter = '08:00:00';
            $officeEndTime = '17:00:00';
            $this->info('Start Regular Attendance execution');
            $userids_of_shifting_duty = HrEmployeeOfficialInfo::where([
                'department_id' => 7, //Personnel
                'section_id' => 18, //Security
                'designation_id' => 48 //Security
            ])->pluck('unique_id')->toArray();

            HrAttendanceRawData::selectRaw("userid,
                    attendance_date,
                    MIN(punch_time) AS new_intime,
                    MAX(punch_time) AS new_outtime")
                ->where('attendance_date', $date)
                ->where('punch_time', '>=', '07:00:00')
                ->whereNotIn('userid', $userids_of_shifting_duty)
                ->groupBy('userid', 'attendance_date')
                ->orderBy('attendance_date')
                ->orderBy('userid')
                ->chunk(200, function ($attendance_raw_datas, $key) use ($officeEndTime) {
                    foreach ($attendance_raw_datas as $attendance_raw_data) {
                        $date = $attendance_raw_data->attendance_date;
                        $attendanceDetails = (new CalculateAttendanceSummary($attendance_raw_data, $officeEndTime, $date))->handle();

                        $attendanceDetailsDataFormatted = collect($attendanceDetails)->except([
                            'approvedOtHourStart',
                            'approvedOtHourEnd',
                            'regularOtHourStart',
                            'regularOtHourEnd',
                            'extraOtHourStart',
                            'extraOtHourEnd',
                            'unapprovedOtHourStart',
                            'unapprovedOtHourEnd',
                        ])->toArray();

                        $attendance_summary = HrAttendanceSummary::where([
                            'userid' => $attendanceDetails['userid'],
                            'date' => $attendanceDetails['date']
                        ])->first();
                        if (!$attendance_summary) {
                            HrAttendanceSummary::create($attendanceDetails);
                        } else {
                            HrAttendanceSummary::where([
                                'userid' => $attendanceDetails['userid'],
                                'date' => $attendanceDetails['date']
                            ])->update($attendanceDetailsDataFormatted);
                        }
                    }
                });
            $this->info('End Regular Attendance execution');

            $this->info('Start Night Attendance execution');
            $this->processNightOtData($date);
            $this->info('End Night Attendance execution');

            $this->info('End execution');

            DB::commit();
            echo 'success';
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

    }

    /**
     * Process Night OT Data
     *
     * @param $date
     * @param $request
     * @return mixed
     */
    private function processNightOtData($date, $userid = '')
    {
        $userid = isset($userid) ? $userid : null;
        $nightOtEndTime = '06:59:00';
        $userids_of_shifting_duty = HrEmployeeOfficialInfo::where([
            'department_id' => 7, //Personnel
            'section_id' => 18, //Security
            'designation_id' => 48 //Security
        ])->pluck('unique_id')->toArray();

        return HrAttendanceRawData::selectRaw("userid,
                    attendance_date,
                    MIN(punch_time) AS night_intime,
                    MAX(punch_time) AS night_outtime")
            ->where('attendance_date', $date)
            ->when($userid != null, function ($query) use ($userid) {
                return $query->where('userid', $userid);
            })
            ->where('punch_time', '<', '07:00:00')
            ->whereNotIn('userid', $userids_of_shifting_duty)
            ->groupBy('userid', 'attendance_date')
            ->orderBy('attendance_date')
            ->orderBy('userid')
            ->chunk(200, function ($attendance_raw_datas, $key) use ($nightOtEndTime) {
                foreach ($attendance_raw_datas as $attendance_raw_data) {
                    $date = $attendance_raw_data->attendance_date;
                    $userid = $attendance_raw_data->userid;

                    $attendance_summary = HrAttendanceSummary::where([
                        'userid' => $userid,
                        'date' => $date
                    ])->first();

                    if ($attendance_summary) {
                        $attendanceDetailsForNightOt = (new CalculateAttendanceSummaryForNightOt($attendance_raw_data, $nightOtEndTime, $date))->handle();

                        HrAttendanceSummary::where([
                            'userid' => $userid,
                            'date' => $date
                        ])->update($attendanceDetailsForNightOt);
                    }
                }
            });
    }

}
