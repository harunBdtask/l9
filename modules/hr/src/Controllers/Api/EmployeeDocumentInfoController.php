<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;
use SkylarkSoft\GoRMG\HR\Repositories\EmployeeDocumentInfoRepository;
use SkylarkSoft\GoRMG\HR\Requests\EmployeeDocumentInfoRequest;
use SkylarkSoft\GoRMG\HR\Resources\EmployeeDocumentInfoResource;

class EmployeeDocumentInfoController extends Controller
{

    public function index(HrEmployee $employee)
    {
        $employeeRepo = new EmployeeDocumentInfoRepository();
        $apiResponse = new ApiResponse($employeeRepo->get($employee), EmployeeDocumentInfoResource::class);
        return $apiResponse->getResponse();
    }

    public function store(HrEmployee $employee, EmployeeDocumentInfoRequest $request)
    {
        $employeeDocumentInfo = new EmployeeDocumentInfoRepository();
        // return $employeeDocumentInfo->store($employee, $request);
        $apiResponse = new ApiResponse($employeeDocumentInfo->store($employee, $request), EmployeeDocumentInfoResource::class);
        return $apiResponse->getResponse();
    }
}
