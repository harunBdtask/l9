<?php

namespace SkylarkSoft\GoRMG\HR\Repositories;


use SkylarkSoft\GoRMG\HR\Requests\EmployeeJobExperienceRequest;

class EmployeeJobExperienceRepository
{
    public function get($employee)
    {
        return $employee->jobExperiences;
    }

    public function store($employee, EmployeeJobExperienceRequest $request)
    {
        return $employee->jobExperiences()->createMany($request["employee_job_experiences"]);
    }
}
