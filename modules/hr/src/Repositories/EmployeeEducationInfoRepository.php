<?php

namespace SkylarkSoft\GoRMG\HR\Repositories;


use SkylarkSoft\GoRMG\HR\Requests\EmployeeEducationInfoRequest;

class EmployeeEducationInfoRepository
{
    public function get($employee)
    {
        return $employee->educations;
    }

    public function store($employee, EmployeeEducationInfoRequest $request)
    {
        return $employee->educations()->createMany($request["employee_educations"]);
    }
}
