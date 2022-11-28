<?php


namespace SkylarkSoft\GoRMG\HR\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SkylarkSoft\GoRMG\HR\Helpers\CalculateAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Helpers\CalculateAttendanceSummaryForNightOt;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceRawData;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;

class GenerateAttendanceSummaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//    public $afterCommit = true;
    public $timeout = 300;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $date;

    public function __construct($attendanceDate)
    {
        $this->date = $attendanceDate;;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $date = Carbon::make($this->date);
//        $date_from = Carbon::now()->startOfMonth();
//        $date_to = Carbon::now()->endOfMonth();
        $date_from = $date;
        $date_to = $date;
        $this->processAttendanceSummary($date_from, $date_to);
        $this->processNightOtData($date_from, $date_to);
    }

    public function processAttendanceSummary(Carbon $from_date, Carbon $to_date)
    {
        $from_date = $from_date->copy()->toDateString();
        $to_date = $to_date->copy()->toDateString();
        $officeEndTime = '17:00:00';
        $userids_of_shifting_duty = HrEmployeeOfficialInfo::where([
            'department_id' => 7, //Personnel
            'section_id' => 18, //Security
            'designation_id' => 48 //Security
        ])->pluck('unique_id')->toArray();

        return HrAttendanceRawData::selectRaw("userid,
                    attendance_date,
                    MIN(punch_time) AS new_intime,
                    MAX(punch_time) AS new_outtime")
            ->where('attendance_date', '>=', $from_date)
            ->where('attendance_date', '<=', $to_date)
            ->where('punch_time', '>=', '06:00:00')
            ->whereNotIn('userid', $userids_of_shifting_duty)
            ->groupBy('userid', 'attendance_date')
            ->orderBy('attendance_date')
            ->orderBy('userid')
            ->chunk(500, function ($attendance_raw_datas, $key) use ($officeEndTime) {
                foreach ($attendance_raw_datas as $attendance_raw_data) {
                    $date = $attendance_raw_data->attendance_date;
                    $attendanceDetails = (new CalculateAttendanceSummary($attendance_raw_data, $officeEndTime, $date))->handle();

                    /*$attendanceDetailsDataFormatted = collect($attendanceDetails)->except([
                        'approvedOtHourStart',
                        'approvedOtHourEnd',
                        'regularOtHourStart',
                        'regularOtHourEnd',
                        'extraOtHourStart',
                        'extraOtHourEnd',
                        'unapprovedOtHourStart',
                        'unapprovedOtHourEnd',
                    ])->toArray();*/

                    HrAttendanceSummary::where([
                        'userid' => $attendanceDetails['userid'],
                        'date' => $attendanceDetails['date']
                    ])->forceDelete();
                    HrAttendanceSummary::create($attendanceDetails);
                }
            });
    }

    /**
     * Processing Night OT Data
     *
     * @param $from_date
     * @param $to_date
     * @return mixed
     */
    private function processNightOtData(Carbon $from_date, Carbon $to_date)
    {
        $from_date = $from_date->copy()->toDateString();
        $to_date = $to_date->copy()->toDateString();
        $nightOtEndTime = '06:59:00';
//        $userids_of_shifting_duty = HrEmployeeOfficialInfo::where([
//            'department_id' => 7, //Personnel
//            'section_id' => 18, //Security
//            'designation_id' => 48 //Security
//        ])->pluck('unique_id')->toArray();

        $userids_of_employee = HrEmployeeOfficialInfo::query()
            ->pluck('punch_card_id')->toArray();

        return HrAttendanceRawData::selectRaw("userid,
                    attendance_date,
                    MIN(punch_time) AS night_intime,
                    MAX(punch_time) AS night_outtime")
            ->where('attendance_date', '>=', $from_date)
            ->where('attendance_date', '<=', $to_date)
            ->where('punch_time', '>=', '17:00:00')
            ->whereIn('userid', $userids_of_employee)
//            ->whereNotIn('userid', $userids_of_shifting_duty)
            ->groupBy('userid', 'attendance_date')
            ->orderBy('attendance_date')
            ->orderBy('userid')
            ->chunk(500, function ($attendance_raw_datas, $key) use ($nightOtEndTime) {
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
