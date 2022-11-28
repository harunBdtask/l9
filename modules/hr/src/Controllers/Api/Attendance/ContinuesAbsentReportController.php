<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;
use Throwable;

class ContinuesAbsentReportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'month' => 'required',
        ]);

        $employeesUniqueId = $this->fetchEmployeesUniqueIDByFilter($request);

        list($year, $month) = explode('-', $request->input('month'));

        $absentFor7Days = DB::table('hr_machine_attendances')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereIn('userid', $employeesUniqueId)
            ->whereNull('att_in')
            ->select('userid', 'date', 'id')
            ->get()
            ->groupBy('userid')
            ->filter(Closure::fromCallable([$this, 'hasCount7OrMore']))
            ->map(Closure::fromCallable([$this, 'mapToIntegerDates']))
            ->filter(Closure::fromCallable([$this, 'isContinues7DaysAbsent']));

        $employees = HrEmployee::with([
            "officialInfo.departmentDetails",
            "officialInfo.designationDetails",
            "officialInfo.sectionDetails"
        ])->whereIn('unique_id', $absentFor7Days
            ->keys()
            ->toArray())
            ->get();

        try {
            $leaves = $absentFor7Days;
            $view = view('hr::attendance.continues-absent', compact('employees', 'leaves'))->render();
        } catch (Throwable $e) {
            $view = '<h1>Something Went Wrong!</h1>';
            $message = $e->getMessage();
            $view = "<h1>$message</h1>";
        }

        return response(compact('view'), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function fetchEmployeesUniqueIDByFilter($request = null)
    {
        $query = HrEmployee::with('officialInfo');

        if ($department = $request->department) {
            $query->whereHas('officialInfo', function ($q) use ($department) {
                $q->where('department_id', $department);
            });
        }

        if ($section = $request->section) {
            $query->whereHas('officialInfo', function ($q) use ($section) {
                $q->where('section_id', $section);
            });
        }

        if ($designation = $request->designation) {
            $query->whereHas('officialInfo', function ($q) use ($designation) {
                $q->where('designation_id', $designation);
            });
        }

        if ($type = $request->type) {
            $query->whereHas('officialInfo', function ($q) use ($type) {
                $q->where('type', $type);
            });
        }

        return $query->pluck('unique_id')->toArray();
    }

    public function hasCount7OrMore($group)
    {
        return count($group) >= 7;
    }

    public function mapToIntegerDates($group)
    {
        return collect($group->pluck('date'))->map(function ($date) {
            return (int)Carbon::parse($date)->format('d');
        });
    }

    public function isContinues7DaysAbsent($group)
    {
        $prevNum = null;
        $total = 1;

        foreach ($group as $item) {
            if (! $prevNum) {
                $prevNum = $item;
            } else {
                if (($prevNum + 1) === $item) {
                    $total += 1;
                } else {
                    $total = 1;
                }
                $prevNum = $item;
            }
        }

        return $total >= 7;
    }
}
