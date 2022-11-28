<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Models\HrMonthlyPaymentSummary;
use SkylarkSoft\GoRMG\HR\Requests\EmployeeMonthlyAttendanceRequest;
use SkylarkSoft\GoRMG\HR\Services\EmployeeMonthlyAttendanceService;

class AllEmployeesMonthlyAttendanceSummaryController extends Controller
{
    public function __invoke(EmployeeMonthlyAttendanceRequest $request)
    {
        $data = EmployeeMonthlyAttendanceService::getEmployeeInformation($request);

        $view = view('hr::reports.all_employees_monthly_attendance_summary', $data);
        $view = $view->render();
        return $view;
    }
}
