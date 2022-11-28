<?php

namespace SkylarkSoft\GoRMG\HR\Helpers;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\HR\Models\HrAttendance;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Models\HrOfficeTimeSetting;
use SkylarkSoft\GoRMG\HR\Models\HrOtApproval;
use SkylarkSoft\GoRMG\HR\Models\HrOtApprovalDetail;

class CalculateAttendanceSummary
{
    protected $date;
    protected $attendanceItem;
    protected $officeEndTime;

    public function __construct($attendanceItem, $officeEndTime, $date)
    {
        $this->date = $date;
        $this->attendanceItem = $attendanceItem;
        $this->officeEndTime = $officeEndTime;
        return true;
    }

    public function handle()
    {
        $date = $this->date;
        $attendanceItem = $this->attendanceItem;
        $officeEndTime = $this->officeEndTime;

        $userid = $attendanceItem->userid;
        $section_id = $attendanceItem->employeeOfficialInfo->section_id;
        $intime = $attendanceItem->new_intime;
        $outtime = $attendanceItem->new_outtime;

        /* total work hour */
        $officeHours = $this->calculateTotalWorkHour($date, $intime, $outtime);

        /* OT Eligible Status Calculation */
        $ot_eligible_status = $this->calculateOtEligibleStatus($date, $userid, $section_id, $outtime, $officeEndTime);

        /* Present Status Calculation */
        $present_status = $this->calculatePresentStatus($date, $userid, $outtime, $officeEndTime);

        $overTimeStart = new Carbon($date . 'T' . $officeEndTime);
        $user_last_punch = new Carbon($date . 'T' . $outtime);

        $attendanceDetails = $this->calculateOvertime($date, $section_id, $overTimeStart, $user_last_punch, $officeEndTime, $officeHours);

        $lunch_in = null;
        $lunch_out = null;
        $lunch_query = HrAttendance::whereDate('date', $date)->where('userid', $userid)->first();
        if ($lunch_query) {
            $lunch_in = $lunch_query->att_break;
            $lunch_out = $lunch_query->att_resume;
        }

        $attendanceDetails['userid'] = $userid;
        $attendanceDetails['date'] = date('Y-m-d', strtotime($attendanceItem->attendance_date));
        $attendanceDetails['att_in'] = $intime;
        $attendanceDetails['status'] = $this->isLateValidation($date, $intime, $userid);
        $attendanceDetails['att_out'] = $outtime;
        $attendanceDetails['lunch_in'] = $lunch_in;
        $attendanceDetails['lunch_out'] = $lunch_out;
        $attendanceDetails['total_work_hour'] = $officeHours;

        $attendanceDetails['extra_ot_hour_next_day'] = null;
        $attendanceDetails['present_status'] = $present_status;
        $attendanceDetails['working_day_type'] = Carbon::parse($date)->isFriday() ? 2 : 1;
        $attendanceDetails['ot_eligible_status'] = $ot_eligible_status;

        /* in minute format */
        $attendanceDetails['total_work_minute'] = $this->minutes($officeHours);
        $attendanceDetails['approved_ot_minute'] = $this->minutes($attendanceDetails['approved_ot_hour'] ?? '00:00:00');
        $attendanceDetails['total_in_day_ot_minute'] = TimeCalculator::calculateOtMinute($attendanceDetails['total_in_day_ot_hour'] ?? '00:00:00');
        $attendanceDetails['regular_ot_minute'] = TimeCalculator::calculateOtMinute($attendanceDetails['regular_ot_hour'] ?? '00:00:00');
        $attendanceDetails['extra_ot_minute_same_day'] = TimeCalculator::calculateOtMinute($attendanceDetails['extra_ot_hour_same_day'] ?? '00:00:00');
        $attendanceDetails['unapproved_ot_minute'] = TimeCalculator::calculateOtMinute($attendanceDetails['unapproved_ot_hour'] ?? '00:00:00');
        $attendanceDetails['extra_ot_minute_next_day'] = null;

        return $attendanceDetails;
    }

    private function calculateOvertime($date, $section_id, Carbon $overTimeStart, $user_last_punch, $officeEndTime, $officeHours)
    {
        $total_in_day_ot_hour = null;
        $approved_ot_minute = null;
        $approvedOtHourStart = null;
        $approvedOtHourEnd = null;
        $approvedOtHour = null;
        $regularOtHourStart = null;
        $regularOtHourEnd = null;
        $extraOtHourStart = null;
        $extraOtHourEnd = null;
        $regularOtHour = null;
        $extraOtHourSameDay = null;
        $unapprovedOtHourStart = null;
        $unapprovedOtHourEnd = null;
        $unapprovedOtHour = null;
        if ($this->minutes($officeHours) <= 540) {
            return [
                'approvedOtHourStart' => $approvedOtHourStart,
                'approvedOtHourEnd' => $approvedOtHourEnd,
                'approved_ot_hour' => $approvedOtHour,
                'total_in_day_ot_hour' => $total_in_day_ot_hour,
                'regularOtHourStart' => $regularOtHourStart,
                'regularOtHourEnd' => $regularOtHourEnd,
                'regular_ot_hour' => $regularOtHour,
                'extraOtHourStart' => $extraOtHourStart,
                'extraOtHourEnd' => $extraOtHourEnd,
                'extra_ot_hour_same_day' => $extraOtHourSameDay,
                'unapprovedOtHourStart' => $unapprovedOtHourStart,
                'unapprovedOtHourEnd' => $unapprovedOtHourEnd,
                'unapproved_ot_hour' => $unapprovedOtHour,
            ];
        }
        /* original overtime calculation */
        $total_in_day_ot_hour = $overTimeStart->diff($user_last_punch)->format('%H:%I:%S');

        /* ot approval data calculation */
        $general_ot_approval = HrOtApprovalDetail::whereDate('ot_date', $date)
            ->where('ot_for', HrOtApproval::GENERAL_OT)
            ->where('section_id', $section_id)
            ->first();

        $unapprovedOtHour = $total_in_day_ot_hour;

        if ($general_ot_approval) {
            $approvedOtHourStart = Carbon::parse($general_ot_approval->ot_date . 'T' . $general_ot_approval->ot_start_time);
            $approvedOtHourEnd = Carbon::parse($general_ot_approval->ot_date . 'T' . $general_ot_approval->ot_end_time);
            $approvedOtHour = $approvedOtHourStart->diff($approvedOtHourEnd)->format('%H:%I:%S');
            $approved_ot_minute = $this->minutes($approvedOtHour);

            if ($approved_ot_minute > 120) {
                /* regular ot hour calculation */
                $regularOtHourStart = new Carbon($date . 'T' . $officeEndTime);
                $regularOtHourEnd = Carbon::parse($date . 'T' . $officeEndTime)->addHours(2);
                /* extra ot hour calculation same day */
                $extraOtHourStart = Carbon::parse($date . 'T' . $officeEndTime)->addHours(2);
                $extraOtHourEnd = ($user_last_punch <= $approvedOtHourEnd) ? $user_last_punch : $approvedOtHourEnd;


                $graseTime = 15;
                $lastPunchTimeForOT = Carbon::parse($date . ' ' . $officeEndTime)->addMinutes($graseTime);
                if ($user_last_punch < $lastPunchTimeForOT) {
                    $regularOtHour = '00:00:00';
                } elseif ($user_last_punch <= $regularOtHourEnd) {
                    $regularOtHour = $regularOtHourStart->diff($user_last_punch)->format('%H:%I:%S');
                } else {
                    $regularOtHour = $regularOtHourStart->diff($regularOtHourEnd)->format('%H:%I:%S');
                    $extraOtHourSameDay = $extraOtHourStart->diff($extraOtHourEnd)->format('%H:%I:%S');
                }


                /* unapproved ot hour calculation same day */
                $unapprovedOtHourCalculate = $this->calculateUnapprovedOtHour($extraOtHourEnd, $user_last_punch);
                $unapprovedOtHourStart = $unapprovedOtHourCalculate['unapprovedOtHourStart'];
                $unapprovedOtHourEnd = $unapprovedOtHourCalculate['unapprovedOtHourEnd'];
                $unapprovedOtHour = $unapprovedOtHourCalculate['unapprovedOtHour'];
            } else {
                /* regular ot hour calculation */
                $regularOtHourStart = new Carbon($date . 'T' . $officeEndTime);
                $regularOtHourEnd = clone $approvedOtHourEnd;
                $graseTime = 15;
                $lastPunchTimeForOT = Carbon::parse($date . ' ' . $officeEndTime)->addMinutes($graseTime);
                if ($user_last_punch < $lastPunchTimeForOT) {
                    $regularOtHour = '00:00:00';
                } elseif ($user_last_punch <= $regularOtHourEnd) {
                    $regularOtHour = $regularOtHourStart->diff($user_last_punch)->format('%H:%I:%S');
                } else {
                    $regularOtHour = $regularOtHourStart->diff($regularOtHourEnd)->format('%H:%I:%S');
                }

                $extraOtHourSameDay = null;

                /* unapproved ot hour calculation same day */
                $unapprovedOtHourCalculate = $this->calculateUnapprovedOtHour($regularOtHourEnd, $user_last_punch);
                $unapprovedOtHourStart = $unapprovedOtHourCalculate['unapprovedOtHourStart'];
                $unapprovedOtHourEnd = $unapprovedOtHourCalculate['unapprovedOtHourEnd'];
                $unapprovedOtHour = $unapprovedOtHourCalculate['unapprovedOtHour'];
            }
        }
        return [
            'approvedOtHourStart' => isset($approvedOtHourStart) ? $approvedOtHourStart->toDateTimeString() : null,
            'approvedOtHourEnd' => isset($approvedOtHourEnd) ? $approvedOtHourEnd->toDateTimeString() : null,
            'approved_ot_hour' => $approvedOtHour,
            'total_in_day_ot_hour' => $total_in_day_ot_hour,
            'regularOtHourStart' => isset($regularOtHourStart) ? $regularOtHourStart->toDateTimeString() : null,
            'regularOtHourEnd' => isset($regularOtHourEnd) ? $regularOtHourEnd->toDateTimeString() : null,
            'regular_ot_hour' => $regularOtHour,
            'extraOtHourStart' => isset($extraOtHourStart) ? $extraOtHourStart->toDateTimeString() : null,
            'extraOtHourEnd' => isset($extraOtHourEnd) ? $extraOtHourEnd->toDateTimeString() : null,
            'extra_ot_hour_same_day' => isset($extraOtHourSameDay) ? $extraOtHourSameDay : null,
            'unapprovedOtHourStart' => isset($unapprovedOtHourStart) ? $unapprovedOtHourStart->toDateTimeString() : null,
            'unapprovedOtHourEnd' => isset($unapprovedOtHourEnd) ? $unapprovedOtHourEnd->toDateTimeString() : null,
            'unapproved_ot_hour' => $unapprovedOtHour,
        ];
    }

    public function isLateValidation($date, $intime, $uniqueId)
    {
        $officeTimeSetting = HrOfficeTimeSetting::first();

        $entryTime = $date . ' ' . $intime;
        $userType = HrEmployeeOfficialInfo::where('punch_card_id', $uniqueId)->first();
        if ($userType) {
            $type = $userType->type;

            if ($type == 'worker') {
                $officeTime = $officeTimeSetting->worker_late_allowed_minute ?? '08:00:00';
            }
            else if ($type == 'staff') {
                $officeTime = $officeTimeSetting->staff_late_allowed_minute ?? '08:00:00';
            }
            else if ($type == 'management') {
                $officeTime = $officeTimeSetting->management_late_allowed_minute ?? '08:00:00';
            }

            if (Carbon::parse($officeTime)->lt(Carbon::parse($entryTime)->format('H:i:s'))) {
                return 'Late';
            } else {
                return 'Intime';
            }
        }
    }

    function minutes($time)
    {
        $time = explode(':', $time);
        return ($time[0] * 60) + ($time[1]) + ($time[2] / 60);
    }


    private function calculateTotalWorkHour($date, $intime, $outtime)
    {
        $officeStart = new Carbon($date . ' ' . $intime);
        $officeEnd = new Carbon($date . ' ' . $outtime);
        return $officeStart->diff($officeEnd)->format('%H:%I:%S');
    }

    private function calculateOtEligibleStatus($date, $userid, $section_id, $outtime, $officeEndTime)
    {
        $ot_eligible_status = 1;
        $ot_approval = HrOtApprovalDetail::whereDate('ot_date', $date)
            ->where('ot_for', HrOtApproval::GENERAL_OT)
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
        if ($ot_approval) {
            if (strtotime($outtime) >= strtotime($officeEndTime)) {
                $ot_eligible_status = 1;
            }
            $graseTime = 15;
            $lastPunchTimeForOT = Carbon::parse($date . ' ' . $officeEndTime)->addMinutes($graseTime);
            if (Carbon::parse($date . ' ' . $outtime) < $lastPunchTimeForOT) {
                $ot_eligible_status = 0;
            }
        }
        return $ot_eligible_status;
    }

    private function calculatePresentStatus($date, $userid, $outtime, $officeEndTime)
    {
        $present_status = 1;
        $machine_attendance = HrAttendance::whereDate('date', $date)->where('userid', $userid)->first();
        if ($machine_attendance && !$machine_attendance->att_in) {
            $present_status = 0;
        } elseif ($machine_attendance && $machine_attendance->att_in && !$machine_attendance->att_out) {
            $present_status = 0;
        } elseif ($machine_attendance && $machine_attendance->att_in && $machine_attendance->att_out && $machine_attendance->att_out < '17:00') {
            $present_status = 0;
        }

        if (strtotime($outtime) >= strtotime($officeEndTime)) {
            $present_status = 1;
        }
        return $present_status;
    }

    private function calculateUnapprovedOtHour(Carbon $checkHourEnd, Carbon $user_last_punch)
    {
        $unapprovedOtHourStart = ($checkHourEnd < $user_last_punch) ? $checkHourEnd : null;
        $unapprovedOtHourEnd = null;
        if ($unapprovedOtHourStart) {
            $unapprovedOtHourEnd = $user_last_punch;
        }
        $unapprovedOtHour = null;
        if ($unapprovedOtHourStart && $unapprovedOtHourEnd) {
            $unapprovedOtHour = $unapprovedOtHourStart->diff($unapprovedOtHourEnd)->format('%H:%I:%S');
        }
        return [
            'unapprovedOtHourStart' => $unapprovedOtHourStart,
            'unapprovedOtHourEnd' => $unapprovedOtHourEnd,
            'unapprovedOtHour' => $unapprovedOtHour,
        ];
    }

}
