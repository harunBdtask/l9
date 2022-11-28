<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Repositories\EmployeeOfficialInfoRepository;
use SkylarkSoft\GoRMG\HR\Requests\EmployeeOfficialInfoRequest;
use SkylarkSoft\GoRMG\HR\Resources\EmployeeOfficialInfoResource;

class EmployeeOfficialInfoController extends Controller
{

    public function getOfficialInformation(Request $request)
    {
        $employeeRepo = new EmployeeOfficialInfoRepository();
        $apiResponse = new ApiResponse($employeeRepo->get($request), EmployeeOfficialInfoResource::class);
        return $apiResponse->getResponse();
    }

    public function officialInformationStore(EmployeeOfficialInfoRequest $request)
    {
        $employeeOfficialInfo = new EmployeeOfficialInfoRepository();
        $apiResponse = new ApiResponse($employeeOfficialInfo->store($request), EmployeeOfficialInfoResource::class);
        return $apiResponse->getResponse();
    }

    public function reportingTo()
    {
        $staffId  = HrEmployeeOfficialInfo::where('type', HrEmployee::STAFF)->pluck('employee_id');
        $employees = HrEmployee::whereIn('id', $staffId)->select('id', 'first_name', 'last_name')->get();
        return response()->json(['data' => $employees]);
    }
}
