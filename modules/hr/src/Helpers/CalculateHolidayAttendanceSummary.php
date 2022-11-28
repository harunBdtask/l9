<?php

namespace SkylarkSoft\GoRMG\HR\Helpers;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\HR\Models\HrOtApproval;
use SkylarkSoft\GoRMG\HR\Models\HrOtApprovalDetail;

class CalculateHolidayAttendanceSummary
{
    protected $date;
    protected $attendanceItem;
    protected $timeCalculator;

    /**
     * CalculateHolidayAttendanceSummary constructor.
     *
     * @param $attendanceItem
     * @param $date
     */
    public function __construct($attendanceItem)
    {
        $this->attendanceItem = $attendanceItem;
        $this->timeCalculator = new TimeCalculator;
        return $attendanceItem ? true : false;
    }

    public function handle()
    {
        $attendanceItem = $this->attendanceItem;
        $timeCalculator = $this->timeCalculator;

        $date = $attendanceItem->attendance_date;
        $userid = $attendanceItem->userid;
        $section_id = $attendanceItem->employeeOfficialInfo->section_id;
        $att_in = $attendanceItem->new_intime;
        $att_out = $attendanceItem->new_outtime;
        $lunch_start = '13:00:00';
        $lunch_end = '14:00:00';

        /* total work hour */
        $total_work_hour = $this->calculateWorkHour($date, $att_in, $att_out, $lunch_start, $lunch_end, $timeCalculator);
        /* total_work_minute */
        $total_work_minute = $timeCalculator::minutes($total_work_hour);
        $approved_start = null;
        $approved_end = null;
        $approved_hour = null;
        $total_approved_work_hour = null;
        $total_unapproved_work_hour = $total_work_hour;
        $ot_approval = HrOtApprovalDetail::whereDate('ot_date', $date)
            ->where('ot_for', HrOtApproval::GENERAL_OT)
            ->where('section_id', $section_id)
            ->first();
        $ot_eligible_status = 0;
        if ($ot_approval) {
            $approved_start = $ot_approval->ot_start_time;
            $approved_end = $ot_approval->ot_end_time;
            $approved_hour = $timeCalculator::calculateHour($date, $approved_start, $approved_end);
            $ot_eligible_status = 1;
            $calculated_hour = $this->calculateApprovedWorkHour($date, $att_in, $att_out, $approved_start, $approved_end, $total_work_hour, $lunch_start, $lunch_end, $timeCalculator);
            $total_approved_work_hour = $calculated_hour['total_approved_work_hour'];
            $total_unapproved_work_hour = $calculated_hour['total_unapproved_work_hour'];
        }
        $approved_minute = $timeCalculator::minutes($approved_hour);
        $total_approved_work_minute = $timeCalculator::calculateOtMinute($total_approved_work_hour);
        $total_unapproved_work_minute = $timeCalculator::calculateOtMinute($total_unapproved_work_hour);
        return [
            'userid' => $userid,
            'date' => $date,
            'att_in' => $att_in,
            'att_out' => $att_out,
            'total_work_hour' => $total_work_hour,
            'total_work_minute' => $total_work_minute,
            'approved_start' => $approved_start,
            'approved_end' => $approved_end,
            'approved_hour' => $approved_hour,
            'approved_minute' => $approved_minute,
            'total_approved_work_hour' => $total_approved_work_hour,
            'total_approved_work_minute' => $total_approved_work_minute,
            'total_unapproved_work_hour' => $total_unapproved_work_hour,
            'total_unapproved_work_minute' => $total_unapproved_work_minute,
            'ot_eligible_status' => $ot_eligible_status
        ];
    }

    /**
     * Calculate Work Hour
     *
     * @param $date
     * @param $att_in
     * @param $att_out
     * @param $lunch_start
     * @param $lunch_end
     * @param TimeCalculator $timeCalculator
     * @return false|int|string
     */
    private function calculateWorkHour($date, $att_in, $att_out, $lunch_start, $lunch_end, TimeCalculator $timeCalculator)
    {
        $att_in_date_time = Carbon::parse($date . ' ' . $att_in);
        $att_out_date_time = Carbon::parse($date . ' ' . $att_out);
        $lunch_start_date_time = Carbon::parse($date . ' ' . $lunch_start);
        $lunch_end_date_time = Carbon::parse($date . ' ' . $lunch_end);

        /* total work hour */
        return $this->calculateHourExcludingLunchHour($date, $att_in_date_time, $att_out_date_time, $lunch_start_date_time, $lunch_end_date_time, $timeCalculator);
    }

    /**
     * Calculate Hour Excluding Lunch Hour
     *
     * @param $date
     * @param Carbon $start_date_time
     * @param Carbon $end_date_time
     * @param Carbon $lunch_start_date_time
     * @param Carbon $lunch_end_date_time
     * @param TimeCalculator $timeCalculator
     * @return false|int|string
     */
    private function calculateHourExcludingLunchHour($date, Carbon $start_date_time, Carbon $end_date_time, Carbon $lunch_start_date_time, Carbon $lunch_end_date_time, TimeCalculator $timeCalculator)
    {
        if ($start_date_time >= $lunch_start_date_time && $end_date_time <= $lunch_end_date_time) {
            return 0;
        }
        $first_segment_time = '00:00:00';
        $second_segment_time = '00:00:00';
        if ($start_date_time < $lunch_start_date_time) {
            $first_segment_time = $timeCalculator::calculateHour($date, $start_date_time->copy()->toTimeString(), $lunch_start_date_time->copy()->toTimeString());
        }
        if ($lunch_end_date_time < $end_date_time) {
            $second_segment_time = $timeCalculator::calculateHour($date, $lunch_end_date_time->copy()->toTimeString(), $end_date_time->copy()->toTimeString());
        }
        return $timeCalculator::addTwoTimesInTimeStamp($first_segment_time, $second_segment_time);
    }

    /**
     * Calculate Approved And Unapproved Work Hour
     *
     * @param $date
     * @param $att_in
     * @param $att_out
     * @param $approved_start
     * @param $approved_end
     * @param TimeCalculator $timeCalculator
     * @return array
     */
    private function calculateApprovedWorkHour($date, $att_in, $att_out, $approved_start, $approved_end, $total_work_hour, $lunch_start, $lunch_end, TimeCalculator $timeCalculator)
    {
        $employee_start_date_time = Carbon::parse($date . 'T' . $att_in);
        $employee_end_date_time = Carbon::parse($date . 'T' . $att_out);
        $approved_start_date_time = Carbon::parse($date . 'T' . $approved_start);
        $approved_end_date_time = Carbon::parse($date . 'T' . $approved_end);
        $lunch_start_date_time = Carbon::parse($date . 'T' . $lunch_start);
        $lunch_end_date_time = Carbon::parse($date . 'T' . $lunch_end);

        $total_hour = $total_work_hour;
        $calculated_hour = [
            'total_approved_work_hour' => null,
            'total_unapproved_work_hour' => null,
        ];
        $calculated_hour['total_unapproved_work_hour'] = $total_hour;
        if ($employee_end_date_time < $approved_start_date_time || $approved_end_date_time < $employee_start_date_time) {
            $calculated_hour['total_approved_work_hour'] = null;
            return $calculated_hour;
        }
        if ($employee_start_date_time >= $approved_start_date_time && $employee_end_date_time <= $approved_end_date_time) {
            $calculated_hour['total_approved_work_hour'] = $total_hour;
            $calculated_hour['total_unapproved_work_hour'] = null;
            return $calculated_hour;
        }
        if ($employee_start_date_time < $approved_start_date_time && $employee_end_date_time > $approved_end_date_time) {
            $calculated_hour['total_approved_work_hour'] = $this->calculateHourExcludingLunchHour($date, $approved_start_date_time, $approved_end_date_time, $lunch_start_date_time, $lunch_end_date_time, $timeCalculator);
            $calculated_hour['total_unapproved_work_hour'] = $timeCalculator::addTwoTimes($att_in, $att_out, $approved_start, $approved_end);
            return $calculated_hour;
        }
        if ($employee_start_date_time < $approved_start_date_time && $employee_end_date_time <= $approved_end_date_time) {
            $calculated_hour['total_approved_work_hour'] = $this->calculateHourExcludingLunchHour($date, $approved_start_date_time, $employee_end_date_time, $lunch_start_date_time, $lunch_end_date_time, $timeCalculator);
            $calculated_hour['total_unapproved_work_hour'] = $timeCalculator::calculateHour($date, $att_in, $approved_start);

            return $calculated_hour;
        }
        if ($employee_start_date_time >= $approved_start_date_time && $employee_end_date_time > $approved_end_date_time) {
            $calculated_hour['total_approved_work_hour'] = $this->calculateHourExcludingLunchHour($date, $employee_start_date_time, $approved_end_date_time, $lunch_start_date_time, $lunch_end_date_time, $timeCalculator);
            $calculated_hour['total_unapproved_work_hour'] = $timeCalculator::calculateHour($date, $approved_end, $att_out);
            return $calculated_hour;
        }
        return $calculated_hour;
    }
}
