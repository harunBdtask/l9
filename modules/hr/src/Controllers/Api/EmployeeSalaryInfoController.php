<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;
use SkylarkSoft\GoRMG\HR\Repositories\EmployeeSalaryInfoRepository;
use SkylarkSoft\GoRMG\HR\Requests\EmployeeSalaryInfoRequest;
use SkylarkSoft\GoRMG\HR\Resources\EmployeeSalaryInfoResource;

class EmployeeSalaryInfoController extends Controller
{

    public function index(HrEmployee $employee)
    {
        $employeeRepo = new EmployeeSalaryInfoRepository();
        $apiResponse = new ApiResponse($employeeRepo->get($employee), EmployeeSalaryInfoResource::class);
        return $apiResponse->getResponse();
    }

    public function store(HrEmployee $employee, EmployeeSalaryInfoRequest $request)
    {
        $employee->salary()->delete();
        $employeeSalaryInfo = new EmployeeSalaryInfoRepository();
        $apiResponse = new ApiResponse($employeeSalaryInfo->store($employee, $request), EmployeeSalaryInfoResource::class);
        return $apiResponse->getResponse();
    }
}
