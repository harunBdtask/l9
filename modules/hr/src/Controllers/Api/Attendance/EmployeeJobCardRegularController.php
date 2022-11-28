<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\HR\Helpers\TimeCalculator;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;
use SkylarkSoft\GoRMG\HR\Models\HrFastivalLeave;
use SkylarkSoft\GoRMG\HR\Models\HrHoliday;
use SkylarkSoft\GoRMG\HR\Models\HrLeaveApplicationDetail;
use SkylarkSoft\GoRMG\HR\Services\JobCardService;
use Throwable;

class EmployeeJobCardRegularController extends Controller
{
    /**
     * @var JobCardService
     */
    private $jobCardService;

    /**
     * EmployeeJobCardRegularController constructor.
     * @param JobCardService $jobCardService
     */
    public function __construct(JobCardService $jobCardService)
    {
        $this->jobCardService = $jobCardService;
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $employeeId = $request->input('employeeId');
        list($year, $month) = explode('-', $request->input('month'));

        $employee = HrEmployee::with([
            "officialInfo.departmentDetails",
            "officialInfo.designationDetails",
            "officialInfo.sectionDetails"
        ])->find($employeeId);

        $userId = $employee->unique_id;

        $firstDayOfMonth = Carbon::parse($year . '-' . $month . '-01');
        $lastDayOfMonth = $firstDayOfMonth->copy()->endOfMonth();

        $attendances = [];
        $totalAbsents = 0;
        $totalHolidays = 0;

        CarbonPeriod::create($firstDayOfMonth, $lastDayOfMonth)->forEach(function ($date) use (
            $userId,
            $employeeId,
            &$attendances,
            &$totalAbsents,
            &$totalHolidays
        ) {

            $value = HrAttendanceSummary::where('userid', $userId)->whereDate('date', $date->toDateString())->first();
            $data = [];
            $data['date'] = $date->toDateString();
            $data['day'] = date('D', strtotime($date->toDateString()));
            $carbon_date_instance = Carbon::parse($date->toDateString());
            $isFriday = $carbon_date_instance->copy()->isFriday();
            $type = '';


            if (! $value) {
                $data = $this->jobCardService->emptyData($data);
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

                if ($isFriday) {
                    $data = $this->jobCardService->emptyData($data);
                } elseif ($main_ot) {
                    $data['outtime'] = $this->getRandomOutTime();
                }

                if ($isFriday) {
                    $type = 'Weekend';
                    $totalHolidays++;
                } elseif (! $summary) {
                    $inLeave = $this->getLeaveDetail($value->date, $employeeId);
                    $isHoliday = HrHoliday::whereDate('date', $value->date)->first();
                    $isFestival = HrFastivalLeave::whereDate('leave_date', $value->date)->first();
                    if ($inLeave) {
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
                } else {
                    if ($summary->present_status == 0) {
                        $type = 'Absent';
                        $totalAbsents += 1;
                    } else {
                        $type = 'Present';
                    }
                }

                $data['type'] = $type;
            }

            $attendances[] = $data;
        });

        $monthDays = Carbon::parse($year . '-' . $month . '-01')->daysInMonth;

        $data = [
            'employee'      => $employee,
            'attendances'   => $attendances,
            'year'          => $year,
            'month'         => $month,
            'monthDays'     => $monthDays,
            'totalAbsents'  => $totalAbsents,
            'totalHolidays' => $totalHolidays,
        ];

        try {
            $view = view('hr::reports.job-card-regular', $data)->render();
        } catch (Throwable $e) {
            $view = "<h1>Something Went Wrong!</h1>";
        }

        return response(['view' => $view]);
    }

    private function getRandomOutTime()
    {
        try {
            return Carbon::parse('6:55:10 pm')
                ->addMinutes(random_int(1, 10))
                ->addSeconds(random_int(1, 60))
                ->toTimeString();
        } catch (Exception $e) {
            return Carbon::parse('6:55:10 pm')
                ->addMinutes(5)
                ->toTimeString();
        }
    }

    private function getLeaveDetail($date, $employeeId)
    {
        return HrLeaveApplicationDetail::with('type')
            ->whereDate('leave_date', $date)
            ->where('employee_id', $employeeId)
            ->first();
    }

    private function getTimeDiff($dtime, $atime)
    {
        $nextDay = $dtime > $atime ? 1 : 0;
        $dep = explode(':', $dtime);
        $arr = explode(':', $atime);

        $diff = abs(mktime($dep[0], $dep[1], 0, date('n'), date('j'), date('y')) - mktime($arr[0], $arr[1], 0,
                date('n'), date('j') + $nextDay, date('y')));

        $hours = floor($diff / (60 * 60));
        $mins = floor(($diff - ($hours * 60 * 60)) / (60));
        $secs = floor(($diff - (($hours * 60 * 60) + ($mins * 60))));

        if (strlen($hours) < 2) {
            $hours = "0" . $hours;
        }
        if (strlen($mins) < 2) {
            $mins = "0" . $mins;
        }
        if (strlen($secs) < 2) {
            $secs = "0" . $secs;
        }
//        return $hours . ':' . $mins . ':' . $secs;
        return $hours . ':' . $mins;
    }

    private function calculateOTHour($date, $attIn, $attOut, $otStart, $otEnd)
    {
        $night_start_date_time = Carbon::parse($date . 'T' . $attIn);
        $night_end_date_time = Carbon::parse($date . 'T' . $attOut);
        $approved_night_start_date_time = Carbon::parse($date . 'T' . $otStart);
        $approved_night_end_date_time = Carbon::parse($date . 'T' . $otEnd);

        $total_night_ot_hour = null;

        if ($night_end_date_time < $approved_night_start_date_time || $approved_night_end_date_time < $night_start_date_time) {
            return $total_night_ot_hour;
        }
        if ($night_start_date_time >= $approved_night_start_date_time && $night_end_date_time <= $approved_night_end_date_time) {
            $total_night_ot_hour = $this->calculateHour($date, $attIn, $attOut);
            return $total_night_ot_hour;
        }
        if ($night_start_date_time < $approved_night_start_date_time && $night_end_date_time > $approved_night_end_date_time) {
            $total_night_ot_hour = $this->calculateHour($date, $otStart, $otEnd);
            return $total_night_ot_hour;
        }
        if ($night_start_date_time < $approved_night_start_date_time && $night_end_date_time <= $approved_night_end_date_time) {
            $total_night_ot_hour = $this->calculateHour($date, $otStart, $attOut);
            return $total_night_ot_hour;
        }
        if ($night_start_date_time >= $approved_night_start_date_time && $night_end_date_time > $approved_night_end_date_time) {
            $total_night_ot_hour = $this->calculateHour($date, $attIn, $otEnd);
            return $total_night_ot_hour;
        }
        return $total_night_ot_hour;
    }

    private function calculateHour($date, $start_time, $end_time)
    {
        $start = new Carbon($date . ' ' . $start_time);
        $end = new Carbon($date . ' ' . $end_time);
//        return $start->diff($end)->format('%H:%I:%S');
        return $start->diff($end)->format('%H:%I');
    }

    private function lateStatus($employeeType, $inTime)
    {
        $today = today()->toDateString();

        if (! $inTime) {
            return null;
        }

        try {

            $staffLastInTime = new Carbon($today . ' ' . '08:16');
            $workerLastInTime = new Carbon($today . ' ' . '08:06');
            $inTime = new Carbon($today . ' ' . $inTime);

            if ($employeeType === HrEmployee::WORKER && $inTime->lessThan($workerLastInTime)) {
                return 0;
            }

            if ($employeeType === HrEmployee::STAFF && $inTime->lessThan($staffLastInTime)) {
                return 0;
            }

            return 1;

        } catch (Exception $e) {
            return null;
        }
    }
}
