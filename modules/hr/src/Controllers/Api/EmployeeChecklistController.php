<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;

class EmployeeChecklistController extends Controller
{
    public function index()
    {
        $employees = $this->getEmployeeData();

//        if (\request()->exists('download')) {
//            $pdf = PDF::loadView('reports.checklist-pdf', compact('employees'));
//            $pdf->setPaper('a4' , 'portrait');
////            return $pdf->output();
//            return $pdf->download('htmltopdfview.pdf');
//        }
        return view('hr::reports.checklist', compact('employees'));
    }


    public function getEmployeeData()
    {
        $employees = HrEmployee::with(
            'officialInfo.departmentDetails',
            'officialInfo.designationDetails',
            'officialInfo.grade',
            'document',
            'salary'
        );

        if ($dept = request('dept')) {
            $employees->wherehas('officialInfo', function($query) use($dept) {
                return $query->where('department_id', $dept);
            });
        }

        if ($start = request('start')) {
            $employees->wherehas('officialInfo', function($query) use($start) {
                return $query->where('date_of_joining', '>=', $start);
            });
        }

        if ($end = request('end')) {
            $employees->wherehas('officialInfo', function($query) use ($end) {
                return $query->where('date_of_joining', '<=', $end);
            });
        }

        if ($grade = request('grade')) {
            $employees->whereHas('officialInfo.grade', function ($query) use ($grade) {
               return $query->where('name', $grade);
            });
        }

        $employees = $employees->get()->sortByDesc(function ($employee) {
            return Carbon::parse($employee->officialInfo->date_of_joining)->timestamp;
        })->sortBy(function ($employee) {
            return $employee->officialInfo->unique_id;
        });

        return $employees;
    }
}
