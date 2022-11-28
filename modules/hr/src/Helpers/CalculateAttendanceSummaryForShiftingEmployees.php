<?php

namespace SkylarkSoft\GoRMG\HR\Helpers;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\HR\Models\HrAttendance;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceRawData;
use SkylarkSoft\GoRMG\HR\Models\HrShift;

class CalculateAttendanceSummaryForShiftingEmployees
{
    protected $date;
    protected $daily_roasting;

    public function __construct($daily_roasting, $date)
    {
        $this->date = $date;
        $this->daily_roasting = $daily_roasting;
        return true;
    }

    public function handle()
    {
        $date = $this->date;
        $date_carbon_instance = Carbon::parse($date);
        $daily_roasting = $this->daily_roasting;

        $employee_id = $daily_roasting->employee_id;
        $userid = $daily_roasting->employeeOfficialInfo->unique_id;
        $shift_id = $daily_roasting->shift_id;
        $attendanceItem = HrAttendanceRawData::where([
            'userid' => $userid,
            'attendance_date' => $date,
        ])->get();
        $shift = HrShift::findOrFail($shift_id);
        $shift_start_date_time = Carbon::parse($date . 'T' . $shift->start_time);
        $intime = $this->calculateInTime($attendanceItem, $shift_start_date_time);

        $shift_end_date_time = Carbon::parse($date . 'T' . $shift->end_time);
        $outtime = $this->calculateOutTime($attendanceItem, $date_carbon_instance, $shift_end_date_time, $userid);
        if ($shift_end_date_time < $shift_start_date_time) {
            $shift_end_date_time = Carbon::parse($date_carbon_instance->copy()->addDays(1)->toDateString() . 'T' . $shift->end_time);
            $outtime = $this->calculateOutTime($attendanceItem, $date_carbon_instance, $shift_end_date_time, $userid);
        }

        /* total work hour */
        $officeHours = $this->calculateTotalWorkHour($intime, $outtime);

        /* OT Eligible Status Calculation */
        $ot_eligible_status = 0;

        /* Present Status Calculation */
        $present_status = $this->calculatePresentStatus($outtime, $shift_end_date_time);


        $lunch_in = null;
        $lunch_out = null;
        $lunch_query = HrAttendance::whereDate('date', $date)->where('userid', $userid)->first();
        if ($lunch_query) {
            $lunch_in = $lunch_query->att_break;
            $lunch_out = $lunch_query->att_resume;
        }

        $attendanceDetails['userid'] = $userid;
        $attendanceDetails['date'] = $date;
        $attendanceDetails['att_in'] = isset($intime) ? $intime->copy()->toTimeString() : null;
        $attendanceDetails['status'] = $this->isLateValidation($intime, $shift_start_date_time);
        $attendanceDetails['att_out'] = $outtime ? $outtime->copy()->toTimeString() : null;
        $attendanceDetails['lunch_in'] = $lunch_in;
        $attendanceDetails['lunch_out'] = $lunch_out;
        $attendanceDetails['total_work_hour'] = $officeHours;

        $attendanceDetails['extra_ot_hour_next_day'] = null;
        $attendanceDetails['present_status'] = $present_status;
        $attendanceDetails['working_day_type'] = $daily_roasting->off_day_status == 1 ? 2 : 1;
        $attendanceDetails['ot_eligible_status'] = $ot_eligible_status;

        /* in minute format */
        $attendanceDetails['total_work_minute'] = $this->minutes($officeHours);
        $attendanceDetails['shift_status'] = 1;

        return $attendanceDetails;
    }

    private function calculateInTime($attendanceItem, Carbon $shift_start_date_time)
    {
        $start_time = $shift_start_date_time->copy()->toTimeString();
        $date = $shift_start_date_time->copy()->toDateString();
        $attendanceItemCloneForPreviousTime = clone $attendanceItem;
        $attendanceItemCloneForLaterTime = clone $attendanceItem;
        if ($attendanceItemCloneForPreviousTime->where('punch_time', '<=', $start_time)->sortByDesc('punch_time')->first()) {
            $time = $attendanceItemCloneForPreviousTime->where('punch_time', '<=', $start_time)->sortByDesc('punch_time')->first()->punch_time;
            return Carbon::parse($date . 'T' . $time);
        } elseif ($attendanceItemCloneForLaterTime->where('punch_time', '>', $start_time)->sortBy('punch_time')->first()) {
            $time = $attendanceItemCloneForLaterTime->where('punch_time', '>', $start_time)->sortBy('punch_time')->first()->punch_time;
            return Carbon::parse($date . 'T' . $time);
        } else {
            return null;
        }
    }

    private function calculateOutTime($attendanceItem, Carbon $date_carbon_instance, Carbon $shift_end_date_time, $userid)
    {
        if ($date_carbon_instance->copy()->toDateString() == $shift_end_date_time->copy()->toDateString()) {
            $start_time = $shift_end_date_time->copy()->toTimeString();
            $date = $shift_end_date_time->copy()->toDateString();
            $attendanceItemCloneForPreviousTime = clone $attendanceItem;
            $attendanceItemCloneForLaterTime = clone $attendanceItem;
            if ($attendanceItemCloneForLaterTime->where('punch_time', '>', $start_time)->sortBy('punch_time')->first()) {
                $time = $attendanceItemCloneForLaterTime->where('punch_time', '>', $start_time)->sortBy('punch_time')->first()->punch_time;
                return Carbon::parse($date . 'T' . $time);
            } elseif ($attendanceItemCloneForPreviousTime->where('punch_time', '<=', $start_time)->sortByDesc('punch_time')->first()) {
                $time = $attendanceItemCloneForPreviousTime->where('punch_time', '<=', $start_time)->sortByDesc('punch_time')->first()->punch_time;
                return Carbon::parse($date . 'T' . $time);
            } else {
                return null;
            }
        } else {
            $start_time = $shift_end_date_time->copy()->toTimeString();
            $date = $shift_end_date_time->copy()->toDateString();
            $attendanceItem = HrAttendanceRawData::where([
                'userid' => $userid,
                'attendance_date' => $shift_end_date_time->copy()->toDateString(),
            ])->get();
            $attendanceItemCloneForPreviousTime = clone $attendanceItem;
            $attendanceItemCloneForLaterTime = clone $attendanceItem;
            if ($attendanceItemCloneForLaterTime->where('punch_time', '>', $start_time)->sortBy('punch_time')->first()) {
                $time = $attendanceItemCloneForLaterTime->where('punch_time', '>', $start_time)->sortBy('punch_time')->first()->punch_time;
                return Carbon::parse($date . 'T' . $time);
            } elseif ($attendanceItemCloneForPreviousTime->where('punch_time', '<=', $start_time)->sortByDesc('punch_time')->first()) {
                $time = $attendanceItemCloneForPreviousTime->where('punch_time', '<=', $start_time)->sortByDesc('punch_time')->first()->punch_time;
                return Carbon::parse($date . 'T' . $time);
            } else {
                return null;
            }
        }
    }

    private function calculateTotalWorkHour($intime = '', $outtime = '')
    {
        if ($intime == '' || $outtime == '') {
            return null;
        }
        return $intime->diff($outtime)->format('%H:%I:%S');
    }

    private function calculatePresentStatus($outtime = '', Carbon $shift_end_date_time)
    {
        $present_status = 0;
        if ($outtime != '' && $outtime >= $shift_end_date_time) {
            $present_status = 1;
        }
        return $present_status;
    }

    private function isLateValidation($intime = '', Carbon $shift_start_date_time)
    {
        if ($intime == '') {
            return null;
        }
        $officeTime = $shift_start_date_time->copy()->addMinutes(5);
        if (Carbon::parse($officeTime) <= $intime) {
            return 'Late';
        } else {
            return 'Intime';
        }
    }

    function minutes($time = '')
    {
        if ($time == '') {
            return null;
        }
        $time = explode(':', $time);
        return ($time[0] * 60) + ($time[1]) + ($time[2] / 60);
    }
}
