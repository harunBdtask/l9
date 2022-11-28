<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;
use SkylarkSoft\GoRMG\HR\Repositories\EmployeeJobExperienceRepository;
use SkylarkSoft\GoRMG\HR\Requests\EmployeeJobExperienceRequest;
use SkylarkSoft\GoRMG\HR\Resources\EmployeeJobExperienceResource;

class EmployeeJobExperienceController extends Controller
{
    public function index(HrEmployee $employee)
    {
        $employeeRepo = new EmployeeJobExperienceRepository();
        $apiResponse = new ApiResponse($employeeRepo->get($employee), EmployeeJobExperienceResource::class);
        return $apiResponse->getResponse();
    }

    public function store(HrEmployee $employee, EmployeeJobExperienceRequest $request)
    {
        $employee->jobExperiences()->delete();
        $employeeJobExperience = new EmployeeJobExperienceRepository();
        $apiResponse = new ApiResponse($employeeJobExperience->store($employee, $request), EmployeeJobExperienceResource::class);
        return $apiResponse->getResponse();
    }
}
