<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\Repositories\AttendanceRepository;
use SkylarkSoft\GoRMG\HR\Requests\ManualAttendanceRequest;

class ManualAttendanceController
{
    public function manualAttendance(ManualAttendanceRequest $request)
    {
        return response()->json((new AttendanceRepository())->manualStore($request));
    }

    public function manualAttendanceList(Request $request)
    {
        $attendanceRepository = new AttendanceRepository();
        $data['reports'] = $attendanceRepository->manualAttendanceList($request);
        $view = view('hr::employee.manual_attendance_list', $data)->render();
        return response()->json([
            'view' => $view
        ]);
    }
}
