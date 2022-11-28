<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;

class EmployeeAppointmentLetterController extends Controller
{
    public function __invoke()
    {
        $employeeId = request('employeeId');
        $employee = HrEmployee::with('officialInfo.departmentDetails', 'officialInfo.designationDetails', 'salary')
            ->find($employeeId);
        return view('hr::employee.appointment-letter', compact('employee'));
    }
}
