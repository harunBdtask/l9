<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\HR\Models\HrAttendance;
use Throwable;

class DailyWorkersAbsentReportController extends Controller
{
    /**
     * Report for Daily Absent
     * For: Workers
     * Select: Date
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function __invoke(Request $request)
    {

        $date = $request->date ?: "2020-02-15";
        $type = $request->type ?? null;

        $attendances = HrAttendance::with([
            'employeeOfficialInfo.employeeBasicInfo',
            'employeeOfficialInfo.departmentDetails',
            'employeeOfficialInfo.designationDetails',
            'employeeOfficialInfo.sectionDetails',
            'employeeOfficialInfo.grade',
        ])
            ->whereDate('date', $date)
            ->whereNull('att_in')
            ->when($type != null, function ($query) use ($type) {
                return $query->whereHas('employeeOfficialInfo', function ($query) use ($type) {
                   return $query->where('type', $type);
                });
            })
            ->get()
            ->groupBy('employeeOfficialInfo.departmentDetails.name');

        try {
            $view = view('hr::reports.workers-absent-report', compact('attendances', 'date'))->render();
        } catch (Throwable $e) {
            $error = $e->getMessage();
            $view = "<h1>$error</h1>";
        }

        return \response(['report' => $view]);
    }
}
