<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\Helpers\TimeCalculator;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Models\HrFastivalLeave;
use SkylarkSoft\GoRMG\HR\Models\HrHoliday;
use SkylarkSoft\GoRMG\HR\Models\HrLeaveApplicationDetail;
use SkylarkSoft\GoRMG\HR\Services\JobCardService;
use Throwable;

class EmployeeJobCardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param JobCardService $service
     * @return \Illuminate\Http\Response
     * @throws Throwable
     */
    public function __invoke(Request $request, JobCardService $service)
    {
        $employeeId = $request->employeeId;
        list($year, $month) = $service->extractYearAndMonth($request->month);
        $employee = $service->fetchEmployeeWithId($employeeId);

        $userId = $employee->officialInfo->unique_id;

        $firstDayOfMonth = Carbon::parse($year . '-' . $month . '-01');
        $lastDayOfMonth = $firstDayOfMonth->copy()->endOfMonth();

        $attendances = [];
        $totalAbsents = 0;
        $totalHolidays = 0;
        $totalWorkingDays = 0;

        CarbonPeriod::create($firstDayOfMonth, $lastDayOfMonth)->forEach(function ($date) use (
            $userId,
            $employeeId,
            &$attendances,
            &$totalAbsents,
            &$totalHolidays,
            &$totalWorkingDays
        ) {
            $value = $this->attendanceSummeryForUserInDate($userId, $date);
            $data = [];
            $data['date'] = $date->toDateString();
            $data['day'] = date('D', strtotime($date->toDateString()));
            $carbonDateObj = Carbon::parse($date->toDateString());
            $isFriday = $carbonDateObj->copy()->isFriday();
            $type = '';

            if (! $value) {
                $data['intime'] = null;
                $data['outtime'] = null;
                $data['totalhour'] = null;
                $data['lunchhour'] = null;
                $data['minute'] = null;
                $data['main_ot'] = null;
                $data['extraot'] = null;
                $data['late'] = null;
                $data['type'] = null;

                $inLeave = $this->getLeaveDetail($data['date'], $employeeId);
                $isHoliday = HrHoliday::whereDate('date', $data['date'])->first();
                $isFestival = HrFastivalLeave::whereDate('leave_date', $data['date'])->first();

                if ($isFriday) {
                    $type = 'Weekend';
                    $totalHolidays++;
                } elseif ($inLeave) {
                    $type = $inLeave->type->name;
                } elseif ($isHoliday) {
                    $type = 'Holiday';
                    $totalHolidays++;
                } elseif ($isFestival) {
                    $type = $isFestival->name;
                    $totalHolidays++;
                } else {
                    $type = 'Absent';
                    $totalAbsents += 1;
                }

                $data['type'] = $type;
            }

            if ($value) {
                $summary = $value;
                $totalWorkingDays++;

                $startTime = $summary->att_in ?? null;
                $finishTime = $summary->att_out ?? null;

                $lunch_start = $summary->lunch_in ?? null;
                $lunch_end = $summary->lunch_out ?? null;

                $totalDuration = TimeCalculator::calculateHour($value->date, $startTime, $finishTime);
                $lunchDuration = TimeCalculator::calculateHour($value->date, $lunch_start, $lunch_end);

                $totalhour = $summary->total_work_hour ?? null;
                $minute = $summary->total_work_minute ?? null;
                $main_ot = $isFriday ? null : (isset($summary->regular_ot_minute) ? ($summary->regular_ot_minute / 60) : null);
                $extraot = $isFriday ? null : (isset($summary->extra_ot_minute_same_day) ? ($summary->extra_ot_minute_same_day / 60) : null);


                $data['intime'] = $startTime ?? '';
                $data['outtime'] = $finishTime ?? '';
                $data['totalhour'] = $totalhour;
                $data['lunchhour'] = $lunchDuration;
                $data['minute'] = $minute;
                $data['main_ot'] = $main_ot;
                $data['extraot'] = $extraot;
                $data['late'] = (isset($summary->status) && $summary->status == 'late') ? 1 : 0;
                $data['type'] = 'Present';
            }

            $attendances[] = $data;

        });


//        $attendances = AttendanceSummary::where('userid', $userId)
//            ->whereMonth('date', $month)
//            ->whereYear('date', $year)
//            ->orderBy('date')
//            ->get()->map(function ($value) use ($userId, $employeeId) {
//                $carbon_date_instance = Carbon::parse($value->date);
//                $summary = $value;
//
//                $startTime = $summary->att_in ?? null;
//                $finishTime = $summary->att_out ?? null;
//
//                $lunch_start = $summary->lunch_in ?? null;
//                $lunch_end = $summary->lunch_out ?? null;
//
//                $totalDuration = TimeCalculator::calculateHour($value->date, $startTime, $finishTime);
//                $lunchDuration = TimeCalculator::calculateHour($value->date, $lunch_start, $lunch_end);
//
//                $totalhour = $summary->total_work_hour ?? null;
//                $minute = $summary->total_work_minute ?? null;
//                $main_ot = $carbon_date_instance->copy()
//                    ->isFriday() ? null : (isset($summary->regular_ot_minute) ? ($summary->regular_ot_minute / 60) : null);
//                $extraot = $carbon_date_instance->copy()
//                    ->isFriday() ? null : (isset($summary->extra_ot_minute_same_day) ? ($summary->extra_ot_minute_same_day / 60) : null);
//                $data['date'] = $value->date;
//                $data['day'] = date('D', strtotime($value->date));
//                $data['intime'] = $startTime ?? '';
//                $data['outtime'] = $finishTime ?? '';
//                $data['totalhour'] = $totalhour;
//                $data['lunchhour'] = $lunchDuration;
//                $data['minute'] = $minute;
//                $data['main_ot'] = $main_ot;
//                $data['extraot'] = $extraot;
//                $data['late'] = (isset($summary->status) && $summary->status == 'late') ? 1 : 0;
//                $type = '';
//                if ($carbon_date_instance->copy()->isFriday()) {
//                    $type = 'Weekend';
//                } elseif (! $summary) {
//                    $inLeave = $this->getLeaveDetail($value->date, $employeeId);
//                    $isHoliday = Holiday::whereDate('date', $value->date)->first();
//                    if ($inLeave) {
//                        $type = $inLeave->type->name;
//                    } elseif ($isHoliday) {
//                        $type = 'Holiday';
//                    } else {
//                        $type = 'Absent';
//                    }
//                } else {
//                    if ($summary->present_status == 0) {
//                        $type = 'Absent';
//                    } else {
//                        $type = 'Present';
//                    }
//                }
//                $data['type'] = $type;
//                return $data;
//            });

        $data = compact('employee', 'attendances', 'year', 'month', 'totalAbsents', 'totalWorkingDays');
        $view = view('hr::reports.job-card-detail', $data)->render();

        return response(['view' => $view]);
    }

    private function getLeaveDetail($date, $employeeId)
    {
        return HrLeaveApplicationDetail::with('type')
            ->whereDate('leave_date', $date)
            ->where('employee_id', $employeeId)
            ->first();
    }

    /**
     * @param $userId
     * @param $date
     * @return mixed
     */
    public function attendanceSummeryForUserInDate($userId, $date)
    {
        return HrAttendanceSummary::where('userid', $userId)->whereDate('date', $date->toDateString())->first();
    }

}
