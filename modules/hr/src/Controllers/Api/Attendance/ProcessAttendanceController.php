<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SkylarkSoft\GoRMG\HR\Helpers\CalculateAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Helpers\CalculateAttendanceSummaryForNightOt;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceRawData;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Rules\LessThanOrEqualMonth;

class ProcessAttendanceController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'userid' => 'required',
            'date_from' => ['bail', 'required', new LessThanOrEqualMonth('date_to')],
            'date_to' => ['required', 'after_or_equal:date_from',]
        ], [
            'userid.required' => 'Unique Id is required!',
            'date_from.required' => 'Date from is required!',
            'date_to.required' => 'Date to is required!',
            'date_to.after_or_equal' => 'Date To should be equal or after Date From'
        ]);

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        try {

            DB::beginTransaction();
            $this->processAttendanceSummary($dateFrom, $dateTo, $request);
            $this->processNightOtData($dateFrom, $dateTo, $request);
            DB::commit();

            return response(['success' => true]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Process Attendance
     *
     * @param $date
     * @param Request $request
     */
    public function processAttendanceSummary($from_date, $to_date, $request)
    {
        $userid = $request->userid ?? null;
        $officeEndTime = '17:00:00';

        return HrAttendanceRawData::selectRaw("userid,
                    attendance_date,
                    MIN(punch_time) AS new_intime,
                    MAX(punch_time) AS new_outtime")
            ->where('attendance_date', '>=', $from_date)
            ->where('attendance_date', '<=', $to_date)
            ->when($userid != null, function ($query) use ($userid) {
                return $query->where('userid', $userid);
            })
            ->where('punch_time', '>=', '07:00:00')
            ->groupBy('userid', 'attendance_date')
            ->orderBy('attendance_date')
            ->orderBy('userid')
            ->chunk(10, function ($attendance_raw_datas, $key) use ($officeEndTime) {
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
    }

    /**
     * Processing Night OT Data
     *
     * @param $from_date
     * @param $to_date
     * @param $request
     * @return mixed
     */
    private function processNightOtData($from_date, $to_date, $request)
    {
        $userid = $request->userid ?? null;
        $nightOtEndTime = '06:59:00';

        return HrAttendanceRawData::selectRaw("userid,
                    attendance_date,
                    MIN(punch_time) AS night_intime,
                    MAX(punch_time) AS night_outtime")
            ->where('attendance_date', '>=', $from_date)
            ->where('attendance_date', '<=', $to_date)
            ->when($userid != null, function ($query) use ($userid) {
                return $query->where('userid', $userid);
            })
            ->where('punch_time', '<', '07:00:00')
            ->groupBy('userid', 'attendance_date')
            ->orderBy('attendance_date')
            ->orderBy('userid')
            ->chunk(10, function ($attendance_raw_datas, $key) use ($nightOtEndTime) {
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
