<?php

namespace SkylarkSoft\GoRMG\HR\Repositories;

use SkylarkSoft\GoRMG\HR\Requests\EmployeeSalaryInfoRequest;

class EmployeeSalaryInfoRepository
{

    public function get($employee)
    {
        return $employee->salary;
    }

    public function store($employee, EmployeeSalaryInfoRequest $request)
    {
        return $employee->salary()->create($request->all());
    }
}
