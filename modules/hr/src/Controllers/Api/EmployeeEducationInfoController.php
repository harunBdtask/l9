<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;
use SkylarkSoft\GoRMG\HR\Repositories\EmployeeEducationInfoRepository;
use SkylarkSoft\GoRMG\HR\Requests\EmployeeEducationInfoRequest;
use SkylarkSoft\GoRMG\HR\Resources\EmployeeEducationInfoResource;

class EmployeeEducationInfoController extends Controller
{


    public function index(HrEmployee $employee)
    {
        $employeeRepo = new EmployeeEducationInfoRepository();
        $apiResponse = new ApiResponse($employeeRepo->get($employee), EmployeeEducationInfoResource::class);
        return $apiResponse->getResponse();
    }

    public function store(HrEmployee $employee, EmployeeEducationInfoRequest $request)
    {
        $employee->educations()->delete();
        $employeeEducationInfo  = new EmployeeEducationInfoRepository();
        $apiResponse            = new ApiResponse($employeeEducationInfo->store($employee, $request), EmployeeEducationInfoResource::class);
        return $apiResponse->getResponse();
    }
}
