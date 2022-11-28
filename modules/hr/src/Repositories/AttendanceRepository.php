<?php


namespace SkylarkSoft\GoRMG\HR\Repositories;

use Carbon\Carbon;
use DB, Exception;
use SkylarkSoft\GoRMG\HR\Helpers\CalculateAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Helpers\CalculateAttendanceSummaryForNightOt;
use SkylarkSoft\GoRMG\HR\Helpers\CalculateHolidayAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Models\HrAttendance;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceRawData;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Models\HrFastivalLeave;
use SkylarkSoft\GoRMG\HR\Models\HrHoliday;
use SkylarkSoft\GoRMG\HR\Models\HrHolidayAttendanceSummary;

class AttendanceRepository
{
    /**
     * Attendance List
     *
     * @param $request
     * @return mixed
     */
    public function attendanceList($request)
    {
        $departmentId = $request->department_id;
        $designationId = $request->designation_id;
        $sectionId = $request->section_id;
        $type = $request->type;
        $date = $request->date ?? date('Y-m-d');
        $query = HrEmployee::with('employeeOfficialInfo');

        $query->whereHas('employeeOfficialInfo', function ($query) use ($departmentId, $designationId, $sectionId, $type) {
            if ($departmentId) {
                $query->where(['department_id' => $departmentId]);
            }
            if ($designationId) {
                $query->where(['designation_id' => $designationId]);
            }
            if ($sectionId) {
                $query->where(['section_id' => $sectionId]);
            }
            if ($type) {
                $query->where(['type' => $type]);
            }
        });

        $employees = $query->get();
        $data = $employees->pluck('employeeOfficialInfo.unique_id')->toArray();

        return HrAttendance::where('date', $date)->whereIn('userid', $data)->get()->map(function ($att) use ($employees) {
            $employee = $employees->where('employeeOfficialInfo.unique_id', $att->userid)->first();
            $att->name = $employee->screen_name;
            return $att;
        });

    }

    /**
     * Attendance Checklist
     *
     * @param $request
     * @return mixed
     */
    public function attendanceCheckList($request)
    {
        $departmentId = $request->department_id;
        $designationId = $request->designation_id;
        $sectionId = $request->section_id;
        $type = $request->type;
        $date = $request->date ?? date('Y-m-d');
        $query = HrEmployee::with('employeeOfficialInfo');
        $query->whereHas('employeeOfficialInfo', function ($query) use ($departmentId, $designationId, $sectionId, $type) {
            if ($departmentId) {
                $query->where(['department_id' => $departmentId]);
            }
            if ($designationId) {
                $query->where(['designation_id' => $designationId]);
            }
            if ($sectionId) {
                $query->where(['section_id' => $sectionId]);
            }
            if ($type) {
                $query->where(['type' => $type]);
            }
        });
        $employees = $query->get();
        $data = $employees->pluck('employeeOfficialInfo.unique_id')->toArray();

        return HrAttendance::where('date', $date)
            ->where(function ($query) {
                return $query->orWhereNull('att_in')
                    ->orWhereNull('att_out');
            })
            ->whereIn('userid', $data)
            ->get()->map(function ($att) use ($employees) {
                $employee = $employees->where('employeeOfficialInfo.unique_id', $att->userid)->first();
                $att->name = $employee->screen_name;
                return $att;
            });
    }

    public function absentList($request)
    {
        $userid = $request->userid ?? null;
        $date = $request->date ?? null;
        $type = $request->type ?? null;
        return HrAttendance::whereNotNull('att_in')
            ->where(function ($query) {
                return $query->orWhereNull('att_out')
                    ->orWhere('att_out', '<', '17:00');
            })
            ->when(($userid != null), function ($query) use ($userid) {
                return $query->where('userid', $userid);
            })
            ->when($date != null, function ($query) use ($date) {
                return $query->where('date', $date);
            })
            ->where('manual_absent_status', 0)
            ->when($type != null, function ($query) use ($type) {
                return $query->whereHas('employeeOfficialInfo', function ($query) use ($type) {
                    return $query->where('type', $type);
                });
            })
            ->orderBy('date', 'desc')
            ->paginate();
    }

    /**
     * Attendance List for manual entry
     *
     * @param $request
     * @return mixed
     */
    public function manualAttendanceList($request)
    {
        $departmentId = $request->department_id;
        $designationId = $request->designation_id;
        $sectionId = $request->section_id;
        $type = $request->type;
        $date = $request->date ?? date('Y-m-d');
        $query = HrEmployee::with('employeeOfficialInfo');
        $query->whereHas('employeeOfficialInfo', function ($query) use ($departmentId, $designationId, $sectionId, $type) {
            if ($departmentId) {
                $query->where(['department_id' => $departmentId]);
            }
            if ($designationId) {
                $query->where(['designation_id' => $designationId]);
            }
            if ($sectionId) {
                $query->where(['section_id' => $sectionId]);
            }
            if ($type) {
                $query->where(['type' => $type]);
            }
        });
        $employees = $query->get();
        $data = $employees->pluck('employeeOfficialInfo.unique_id')->toArray();

        return HrAttendanceRawData::whereDate('attendance_date', $date)
            ->where('flag', 'M')
            ->whereIn('userid', $data)
            ->get()->map(function ($att) use ($employees) {
                $employee = $employees->where('employeeOfficialInfo.unique_id', $att->userid)->first();
                $att->name = $employee->screen_name;
                return $att;
            });
    }

    /**
     * Attendance Manual Store
     *
     * @param $request
     * @return array
     */
    public function manualStore($request)
    {

        try {
            $attendance_date = $request->attendance_date;
            $userid = HrEmployeeOfficialInfo::where('employee_id', $request->employee_id)->first()->unique_id;
            $in_time = $request->in_time;
            $out_time = $request->out_time;

            DB::beginTransaction();
            $data = [];
            $attendance_raw_query = HrAttendanceRawData::whereDate('attendance_date', $attendance_date)
                ->where('userid', $userid);
            //In Time Entry
            if ($in_time) {
                $attendance_raw_data_in = new HrAttendanceRawData();
                $attendance_raw_data_in->userid = $userid;
                $attendance_raw_data_in->punch_time = $in_time;
                $attendance_raw_data_in->attendance_date = $attendance_date;
                $attendance_raw_data_in->flag = 'M';
                $attendance_raw_data_in->save();
                $data[] = $attendance_raw_data_in;
            }

            //Out Time Entry
            if ($out_time) {
                $attendance_raw_query_clone = clone $attendance_raw_query;
                $attendance_raw_data_first = $attendance_raw_query_clone->orderBy('punch_time', 'desc')->first();
                /* If requested out_time is less than database last out time then update else insert */
                if ($attendance_raw_query_clone->count() <= 1 && $attendance_raw_data_first->punch_time < $out_time) {
                    $attendance_raw_data_out = new HrAttendanceRawData();
                    $attendance_raw_data_out->created_by = auth()->user()->id ?? null;
                } else {
                    $attendance_raw_data_id = $attendance_raw_data_first->id;
                    $attendance_raw_data_out = HrAttendanceRawData::findOrFail($attendance_raw_data_id);
                    $attendance_raw_data_out->updated_by = auth()->user()->id ?? null;
                }

                $attendance_raw_data_out->userid = $userid;
                $attendance_raw_data_out->punch_time = $out_time;
                $attendance_raw_data_out->attendance_date = $attendance_date;
                $attendance_raw_data_out->flag = 'M';
                $attendance_raw_data_out->save();
                $data[] = $attendance_raw_data_out;
            }

            /* update in machine attendance table */

            $attendance_query = HrAttendance::where(['userid' => $userid, 'date' => $attendance_date])->first();
            if ($attendance_query) {
                $attendance = HrAttendance::findOrFail($attendance_query->id);
                if ($in_time) {
                    $attendance->att_in = $in_time;
                }
                if ($out_time) {
                    $attendance->att_out = $out_time;
                }
                if ($request->lunch_start) {
                    $attendance->att_break = $request->lunch_start;
                }
                if ($request->lunch_end) {
                    $attendance->att_resume = $request->lunch_end;
                }
                $attendance->save();
            }

            $this->updateAttendanceSummaryData($attendance_date, $userid);

            $checkIfDateHoliday = $this->checkIfDateIsHoliday($attendance_date);
            if ($checkIfDateHoliday) {
                $this->updateHolidayAttendanceData($attendance_date, $userid);
            }
            DB::commit();
            return [
                'status' => 'success',
                'error' => null,
                'error_code' => null,
                'data' => $data,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'status' => 'danger',
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'data' => null
            ];
        }
    }

    /**
     * Process Regular Attendance
     *
     * @param $request
     * @return string
     */
    public function processRegularAttendance($request)
    {
        try {
            DB::beginTransaction();
            $date = $request->date ?? (Carbon::now()->subDays(1)->isFriday() ? Carbon::now()->subDays(2)->toDateString() : Carbon::now()->subDays(1)->toDateString());

            $userid = $request->userid ?? null;
            $this->updateAttendanceSummaryData($date, $userid);
            DB::commit();
            return 'success';
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * Update Attendance Summary Data
     *
     * @param $attendance_date
     * @param string $userid
     * @return bool
     */
    private function updateAttendanceSummaryData($attendance_date, $userid = '')
    {
        $date = $attendance_date;
        $userid = ($userid != '') ? $userid : null;

        $officeEndTime = '17:00:00';
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
            ->when($userid != null, function ($query) use ($userid) {
                return $query->where('userid', $userid);
            })
            ->whereNotIn('userid', $userids_of_shifting_duty)
            ->where('punch_time', '>=', '07:00:00')
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

        $this->processNightOtData($attendance_date, $userid);
        return true;
    }

    /**
     * Process Night Ot Data
     *
     * @param $attendance_date
     * @param string $userid
     * @return mixed
     */
    private function processNightOtData($attendance_date, $userid = '')
    {
        $date = $attendance_date;
        $userid = ($userid != '') ? $userid : null;
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
            ->whereNotIn('userid', $userids_of_shifting_duty)
            ->where('punch_time', '<', '07:00:00')
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

    /**
     * Process Holiday Attendance
     *
     * @param $request
     * @return string
     */
    public function processHolidayAttendance($request)
    {
        try {
            DB::beginTransaction();
            $date = $request->date ?? null;
            if (!$date) {
                return "Date is required!";
            }
            $checkIfDateHoliday = $this->checkIfDateIsHoliday($date);
            if (!$checkIfDateHoliday) {
                return "Date must be friday or holiday or festival day!";
            }
            $userid = $request->userid ?? null;
            $this->updateHolidayAttendanceData($date, $userid);
            DB::commit();
            return 'success';
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
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
        $userids_of_shifting_duty = HrEmployeeOfficialInfo::where([
            'department_id' => 7, //Personnel
            'section_id' => 18, //Security
            'designation_id' => 48 //Security
        ])->pluck('unique_id')->toArray();
        return HrAttendanceRawData::selectRaw("userid,
                    attendance_date,
                    MIN(punch_time) AS new_intime,
                    MAX(punch_time) AS new_outtime")
            ->where('attendance_date', $date)
            ->when($userid != null, function ($query) use ($userid) {
                return $query->where('userid', $userid);
            })
            ->whereNotIn('userid', $userids_of_shifting_duty)
            ->groupBy('userid', 'attendance_date')
            ->orderBy('attendance_date')
            ->orderBy('userid')
            ->chunk(200, function ($attendance_raw_datas, $key) {
                foreach ($attendance_raw_datas as $attendance_raw_data) {
                    $holidayAttendanceDetails = (new CalculateHolidayAttendanceSummary($attendance_raw_data))->handle();
                    $attendance_summary = HrHolidayAttendanceSummary::where([
                        'userid' => $holidayAttendanceDetails['userid'],
                        'date' => $holidayAttendanceDetails['date']
                    ])->first();
                    if (!$attendance_summary) {
                        HrHolidayAttendanceSummary::create($holidayAttendanceDetails);
                    } else {
                        HrHolidayAttendanceSummary::where([
                            'userid' => $holidayAttendanceDetails['userid'],
                            'date' => $holidayAttendanceDetails['date']
                        ])->update($holidayAttendanceDetails);
                    }
                }
            });
    }

    /**
     * Check If Date Is Holiday
     *
     * @param $date
     * @return bool
     */
    private function checkIfDateIsHoliday($date)
    {
        $date = Carbon::parse($date);
        $check = false;
        if ($date->copy()->isFriday()) {
            $check = true;
        }
        if (HrFastivalLeave::whereDate('leave_date', $date->copy()->toDateString())->count()) {
            $check = true;
        }
        if (HrHoliday::whereDate('date', $date->copy()->toDateString())->count()) {
            $check = true;
        }
        return $check;
    }
}
