<?php

namespace SkylarkSoft\GoRMG\HR\Repositories;

use SkylarkSoft\GoRMG\HR\Models\HrEmployee;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Requests\EmployeeOfficialInfoRequest;
use Carbon\Carbon;

class EmployeeOfficialInfoRepository
{

    public function get($request)
    {
        return HrEmployeeOfficialInfo::where('employee_id', $request->id)->first();
    }

    public function store(EmployeeOfficialInfoRequest $request)
    {
        try {
            $id = $request->employeeId ?? '';
            $employeeOfficialInfo = HrEmployeeOfficialInfo::firstOrNew(['employee_id' => $id]);
            $job_permanent_date = "";
            if($request->type == "worker") {
                $job_permanent_date = Carbon::parse($request->get('date_of_joining'))->addDays(90)->format('Y-m-d');

            }
            else if($request->type == "staff") {
                $job_permanent_date = Carbon::parse($request->get('date_of_joining'))->addDays(180)->format('Y-m-d');
            }
            $employeeOfficialInfo->fill($request->all());
            $employeeOfficialInfo->job_permanent_date = $job_permanent_date;
            $employeeOfficialInfo->save();
            return $employeeOfficialInfo;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function destroy($id)
    {
        try {
            $employee = HrEmployee::find($id);
            $employee->delete();
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
