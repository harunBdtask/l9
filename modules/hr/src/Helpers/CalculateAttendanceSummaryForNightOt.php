<?php

namespace SkylarkSoft\GoRMG\HR\Helpers;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\HR\Models\HrAttendance;
use SkylarkSoft\GoRMG\HR\Models\HrOtApproval;
use SkylarkSoft\GoRMG\HR\Models\HrOtApprovalDetail;

class CalculateAttendanceSummaryForNightOt
{
    protected $date;
    protected $attendanceItem;
    protected $nightOtEndTime;

    public function __construct($attendanceItem, $nightOtEndTime, $date)
    {
        $this->date = $date;
        $this->attendanceItem = $attendanceItem;
        $this->nightOtEndTime = $nightOtEndTime;
        return true;
    }

    public function handle()
    {
        $date = $this->date;
        $attendanceItem = $this->attendanceItem;
        $nightOtEndTime = $this->nightOtEndTime;

        $userid = $attendanceItem->userid;
        $section_id = $attendanceItem->employeeOfficialInfo->section_id;
        $night_start = $attendanceItem->night_intime;
        $night_end = $attendanceItem->night_outtime;
        $total_night_hour = $this->calculateHour($date, $night_start, $night_end);

        $total_night_minute = $this->minutes($total_night_hour);
        $night_ot_eligible_status = $this->calculateNightOtEligibleStatus($date, $userid, $section_id);

        /* ot approval data calculation */
        $night_ot_approval = HrOtApprovalDetail::whereDate('ot_date', $date)
            ->where('ot_for', HrOtApproval::NIGHT_OT)
            ->where('section_id', $section_id)
            ->first();

        $total_night_ot_hour = $total_night_hour;
        $unapproved_night_ot_hour = $total_night_hour;

        if ($night_ot_approval) {
            $approved_night_start = $night_ot_approval->ot_start_time;
            $approved_night_end = $night_ot_approval->ot_end_time;
            $total_approved_ot_hour = $this->calculateHour($date, $approved_night_start, $approved_night_end);
            $total_approved_ot_minute = $this->minutes($total_approved_ot_hour);
            $calculate_night_ot_hour = $this->calculateNightOtHour($date, $night_start, $night_end, $approved_night_start, $approved_night_end);
            $total_night_ot_hour = $calculate_night_ot_hour['total_night_ot_hour'];
            $unapproved_night_ot_hour = $calculate_night_ot_hour['unapproved_night_ot_hour'];
        }
        $total_night_ot_minute = TimeCalculator::calculateOtMinute($total_night_ot_hour);
        $unapproved_night_ot_minute = TimeCalculator::calculateOtMinute($unapproved_night_ot_hour);

        return [
            'night_start' => $night_start,
            'night_end' => $night_end,
            'total_night_hour' => $total_night_hour,
            'approved_night_start' => isset($approved_night_start) ? $approved_night_start : null,
            'approved_night_end' => isset($approved_night_end) ? $approved_night_end : null,
            'total_approved_ot_hour' => isset($total_approved_ot_hour) ? $total_approved_ot_hour : null,
            'total_night_ot_hour' => isset($total_night_ot_hour) ? $total_night_ot_hour : null,
            'unapproved_night_ot_hour' => isset($unapproved_night_ot_hour) ? $unapproved_night_ot_hour : null,
            'total_night_minute' => $total_night_minute,
            'total_approved_ot_minute' => isset($total_approved_ot_minute) ? $total_approved_ot_minute : null,
            'total_night_ot_minute' => $total_night_ot_minute,
            'unapproved_night_ot_minute' => $unapproved_night_ot_minute,
            'night_ot_eligible_status' => $night_ot_eligible_status
        ];
    }

    /**
     * Calculate time difference HH:mm:ss
     *
     * @param $date
     * @param $start_time
     * @param $end_time
     * @return string
     */
    private function calculateHour($date, $start_time, $end_time)
    {
        $start = new Carbon($date . ' ' . $start_time);
        $end = new Carbon($date . ' ' . $end_time);
        return $start->diff($end)->format('%H:%I:%S');
    }

    /**
     * Convert Time to minutes
     *
     * @param $time
     * @return float|int
     */
    private function minutes($time = '')
    {
        if (!$time) {
            return null;
        }
        $time = explode(':', $time);
        return ($time[0] * 60) + ($time[1]) + ($time[2] / 60);
    }

    /**
     * Calculate Night OT Eligible Status
     *
     * @param $date
     * @param $userid
     * @return int
     */
    private function calculateNightOtEligibleStatus($date, $userid, $section_id)
    {
        $ot_eligible_status = 1;
        $ot_approval = HrOtApprovalDetail::whereDate('ot_date', $date)
            ->where('ot_for', HrOtApproval::NIGHT_OT)
            ->where('section_id', $section_id)
            ->first();

        if (!$ot_approval) {
            $ot_eligible_status = 0;
        }
        $previous_working_date = Carbon::parse($date)->subDays(1)->isFriday() ? Carbon::parse($date)->subDays(2)->toDateTime() : Carbon::parse($date)->subDays(1)->toDateTime();
        $previous_machine_attendance = HrAttendance::whereDate('date', $previous_working_date)->where('userid', $userid)->first();
        if ($previous_machine_attendance && $previous_machine_attendance->att_in && !$previous_machine_attendance->att_out) {
            $ot_eligible_status = 0;
        } elseif ($previous_machine_attendance && $previous_machine_attendance->att_in && $previous_machine_attendance->att_out && $previous_machine_attendance->att_out < '17:00') {
            $ot_eligible_status = 0;
        }
        return $ot_eligible_status;
    }

    /**
     * Calculate Night OT Hour
     *
     * @param $date
     * @param $night_start
     * @param $night_end
     * @param $approved_night_start
     * @param $approved_night_end
     * @return array
     */
    private function calculateNightOtHour($date, $night_start, $night_end, $approved_night_start, $approved_night_end)
    {
        $night_start_date_time = Carbon::parse($date . 'T' . $night_start);
        $night_end_date_time = Carbon::parse($date . 'T' . $night_end);
        $approved_night_start_date_time = Carbon::parse($date . 'T' . $approved_night_start);
        $approved_night_end_date_time = Carbon::parse($date . 'T' . $approved_night_end);

        $total_night_hour = $this->calculateHour($date, $night_start, $night_end);
        $calculate_night_ot_hour = [
            'total_night_ot_hour' => null,
            'unapproved_night_ot_hour' => null,
        ];
        $calculate_night_ot_hour['unapproved_night_ot_hour'] = $total_night_hour;
        if ($night_end_date_time < $approved_night_start_date_time || $approved_night_end_date_time < $night_start_date_time) {
            $calculate_night_ot_hour['total_night_ot_hour'] = null;
            return $calculate_night_ot_hour;
        }
        if ($night_start_date_time >= $approved_night_start_date_time && $night_end_date_time <= $approved_night_end_date_time) {
            $calculate_night_ot_hour['total_night_ot_hour'] = $this->calculateHour($date, $night_start, $night_end);
            $calculate_night_ot_hour['unapproved_night_ot_hour'] = null;
            return $calculate_night_ot_hour;
        }
        if ($night_start_date_time < $approved_night_start_date_time && $night_end_date_time > $approved_night_end_date_time) {
            $calculate_night_ot_hour['total_night_ot_hour'] = $this->calculateHour($date, $approved_night_start, $approved_night_end);
            $calculate_night_ot_hour['unapproved_night_ot_hour'] = $this->addTwoTimes($night_start, $night_end, $approved_night_start, $approved_night_end);
            return $calculate_night_ot_hour;
        }
        if ($night_start_date_time < $approved_night_start_date_time && $night_end_date_time <= $approved_night_end_date_time) {
            $calculate_night_ot_hour['total_night_ot_hour'] = $this->calculateHour($date, $approved_night_start, $night_end);
            $calculate_night_ot_hour['unapproved_night_ot_hour'] = $this->calculateHour($date, $night_start, $approved_night_start);
            return $calculate_night_ot_hour;
        }
        if ($night_start_date_time >= $approved_night_start_date_time && $night_end_date_time > $approved_night_end_date_time) {
            $calculate_night_ot_hour['total_night_ot_hour'] = $this->calculateHour($date, $night_start, $approved_night_end);
            $calculate_night_ot_hour['unapproved_night_ot_hour'] = $this->calculateHour($date, $approved_night_end, $night_end);
            return $calculate_night_ot_hour;
        }
        return $calculate_night_ot_hour;
    }

    /**
     * Add two times
     *
     * @param $night_start
     * @param $night_end
     * @param $approved_night_start
     * @param $approved_night_end
     * @return false|string
     */
    private function addTwoTimes($night_start, $night_end, $approved_night_start, $approved_night_end)
    {
        $one = Carbon::parse($night_start);
        $two = Carbon::parse($approved_night_start);
        $first_segment = $one->diffInSeconds($two);

        $three = Carbon::parse($approved_night_end);
        $four = Carbon::parse($night_end);
        $second_segment = $three->diffInSeconds($four);

        return gmdate('H:i:s', $first_segment + $second_segment);
    }
}
