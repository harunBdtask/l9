<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Exports\EmployeeExport;
use SkylarkSoft\GoRMG\HR\Models\HrDepartment;
use SkylarkSoft\GoRMG\HR\Models\HrDesignation;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Models\HrGrade;
use SkylarkSoft\GoRMG\HR\Models\HrSalaryHistory;
use SkylarkSoft\GoRMG\HR\Repositories\EmployeeRepository;
use SkylarkSoft\GoRMG\HR\Requests\EmployeeRequest;
use SkylarkSoft\GoRMG\HR\Resources\EmployeeDropdownResource;
use SkylarkSoft\GoRMG\HR\Resources\EmployeeResourse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        $departments = HrDepartment::all();
        $designations = HrDesignation::all();
        $grades = HrGrade::all();

        $employees = $this->getEmployeeData($request, 'worker');

        return view('hr::employee.employee-list', compact('employees', 'departments', 'designations', 'grades'));
    }

    public function staffList(Request $request)
    {
        $departments = HrDepartment::all();
        $designations = HrDesignation::all();
        $grades = HrGrade::all();

        $employees = $this->getEmployeeData($request, 'staff');

        return view('hr::employee.staff-list', compact('employees', 'departments', 'designations', 'grades'));
    }

    public function managementList(Request $request)
    {
        $departments = HrDepartment::all();
        $designations = HrDesignation::all();
        $grades = HrGrade::all();

        $employees = $this->getEmployeeData($request, 'management');

        return view('hr::employee.management-list', compact('employees', 'departments', 'designations', 'grades'));
    }

    private function getEmployeeData($request, $emType)
    {
        $uniqueId = $request->get('unique_id');
        $name = $request->get('name');
        $code = $request->get('code');
        $department = $request->get('department');
        $section = $request->get('section');
        $designation = $request->get('designation');
        $gender = $request->get('gender');
        $salary = $request->get('salary');
        $grade = $request->get('grade');

        if ($emType == 'worker') {
            $type = HrEmployee::WORKER;
        } elseif ($emType == 'staff') {
            $type = HrEmployee::STAFF;
        } elseif ($emType == 'management') {
            $type = HrEmployee::MANAGEMENT;
        }

        return HrEmployee::with('salary', 'officialInfo.grade')
            ->whereHas('officialInfo', function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($uniqueId, function ($query) use ($uniqueId) {
                $query->where('unique_id', $uniqueId);
            })
            ->when($name, function ($query) use ($name) {
                $query->where('first_name', 'like', '%' . $name . '%')->orWhere('last_name', 'like', '%' . $name . '%');
            })
            ->when($code, function ($query) use ($code) {
                $query->whereHas('officialInfo', function ($q) use ($code) {
                    $q->where('code', $code);
                });
            })
            ->when($department, function ($query) use ($department) {
                $query->whereHas('officialInfo', function ($q) use ($department) {
                    $q->where('department_id', $department);
                });
            })
            ->when($section, function ($query) use ($section) {
                $query->whereHas('officialInfo', function ($q) use ($section) {
                    $q->where('section_id', $section);
                });
            })
            ->when($designation, function ($query) use ($designation) {
                $query->whereHas('officialInfo', function ($q) use ($designation) {
                    $q->where('designation_id', $designation);
                });
            })
            ->when($designation, function ($query) use ($designation) {
                $query->whereHas('officialInfo', function ($q) use ($designation) {
                    $q->where('designation_id', $designation);
                });
            })
            ->when($gender, function ($query) use ($gender) {
                $query->where('sex', 'like', '%' . $gender . '%');
            })
            ->when($grade, function ($query) use ($grade) {
                $query->whereHas('officialInfo', function ($q) use ($grade) {
                    $q->where('grade_id', $grade);
                });
            })
            ->when($salary, function ($query) use ($salary) {
                $query->whereHas('salary', function ($q) use ($salary) {
                    $q->where('gross', $salary);
                });
            })
            ->latest()
            ->paginate();
    }

    public function salaryHistory(Request $request)
    {
        $employeeId = $request->get('employee_id');
        $designationId = $request->get('designation_id');
        $departmentId = $request->get('department_id');
        $year = $request->get('year');
        $grossSalary = $request->get('gross_salary');

        $employees = HrEmployee::all();
        $departments = HrDepartment::all();
        $designations = HrDesignation::all();

        $histories = HrSalaryHistory::query()
            ->with(['employee', 'department', 'designation'])
            ->when($employeeId, function ($query) use ($employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->when($designationId, function ($query) use ($designationId) {
                $query->where('designation_id', $designationId);
            })
            ->when($departmentId, function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->when($year, function ($query) use ($year) {
                $query->where('year', $year);
            })
            ->when($grossSalary, function ($query) use ($grossSalary) {
                $query->where('gross_salary', $grossSalary);
            })
            ->latest()
            ->paginate();

        return view('hr::employee.salary-history-list', compact('employees', 'departments', 'designations', 'histories'));
    }

    public function workerList()
    {
        $employeeRepo = new EmployeeRepository();
        $apiResponse = new ApiResponse($employeeRepo->workerList(), EmployeeResourse::class);
        return $apiResponse->getResponse();
    }

    public function employeeFullList()
    {
        $employeeRepo = new EmployeeRepository();
        $apiResponse = new ApiResponse($employeeRepo->all(), EmployeeResourse::class);
        return $apiResponse->getResponse();
    }

    public function employeesForSelect()
    {
        $repo = new EmployeeRepository();
        return ApiResponse::create($repo->fetchForSelect(), EmployeeDropdownResource::class)->getResponse();
    }

    public function employeesForDailyRoasting()
    {
        $repo = new EmployeeRepository();
        return ApiResponse::create($repo->dailyRoastingEmployees(), EmployeeDropdownResource::class)->getResponse();
    }

    public function forApplication()
    {
        $employee = HrEmployee::with([
            "officialInfo.departmentDetails",
            "officialInfo.designationDetails",
            "officialInfo.sectionDetails"
        ])->findOrFail(request('employeeId'));

        return response(['data' => $employee], Response::HTTP_OK);
    }

    public function getEmployeeUniqueIds()
    {
        return HrEmployeeOfficialInfo::select('unique_id', 'employee_id')->orderBy('unique_id', 'asc')->get();
    }

    public function getEmployeeUniqueIdsByDept($deptId) {
        $hrEmployeesIDs = HrEmployeeOfficialInfo::where('department_id', $deptId)
            ->select('employee_id', 'unique_id')
            ->orderBy('unique_id', 'asc')
            ->get();

        return $hrEmployeesIDs;
    }

    public function getEmployeeUniqueIdsByDesignation($designationId) {
        $hrEmployeesIDs = HrEmployeeOfficialInfo::where('designation_id', $designationId)
            ->select('employee_id', 'unique_id')
            ->orderBy('unique_id', 'asc')
            ->get();

        return $hrEmployeesIDs;
    }

    public function getEmployeeUniqueIdsBySection($sectionId) {
        $hrEmployeesIDs = HrEmployeeOfficialInfo::where('section_id', $sectionId)
        ->select('employee_id', 'unique_id')
        ->orderBy('unique_id', 'asc')
        ->get();

        return $hrEmployeesIDs;
    }

    public function store(EmployeeRequest $request)
    {
        $employeeRepo = new EmployeeRepository();
        $apiResponse = new ApiResponse($employeeRepo->store($request), EmployeeResourse::class);
        return $apiResponse->getResponse();
    }


    public function show($id)
    {
        try {
            $employee = HrEmployee::query()
                ->where('id', $id)
                ->with([
                    'officialInfo.departmentDetails',
                    'officialInfo.designationDetails',
                    'officialInfo.sectionDetails',
                    'officialInfo.grade',
                    'jobExperiences',
                    'educations',
                    'salary',
                    'document'
                ])
                ->firstOrFail();

            return response()->json($employee, 200);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function profile($id)
    {
        $employee = HrEmployee::with([
            'officialInfo.departmentDetails',
            'officialInfo.designationDetails',
            'officialInfo.sectionDetails',
            'officialInfo.grade',
            'jobExperiences',
            'educations',
            'salary',
            'document'
        ])->find($id);

        try {
            $view = view('hr::employee.profile', compact('employee'))->render();
            return response($view);
        } catch (Throwable $e) {
            return response('<h1>Something Went Wrong!!</h1>');
        }
    }

    public function update($id, EmployeeRequest $request)
    {

        $employeeRepo = new EmployeeRepository();
        $apiResponse = new ApiResponse($employeeRepo->update($request), EmployeeResourse::class);
        return $apiResponse->getResponse();
    }

    public function destroy($id)
    {
        $employeeRepo = new EmployeeRepository();
        $apiResponse = new ApiResponse($employeeRepo->destroy($id), EmployeeResourse::class);

        session()->flash('success', 'Delete Success.');
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $employeeRepo = new EmployeeRepository();
        // return $employeeRepo->search($request);
        $apiResponse = new ApiResponse($employeeRepo->search($request), EmployeeResourse::class);
        return $apiResponse->getResponse();
    }

    public function listSearch(Request $request)
    {
        $employeeRepo = new EmployeeRepository();
        // return $employeeRepo->search($request);
        $apiResponse = new ApiResponse($employeeRepo->listSearch($request), EmployeeResourse::class);
        return $apiResponse->getResponse();
    }

    public function employeeBySection()
    {
        $sectionId = request('sectionId');
        return HrEmployeeOfficialInfo::where('section_id', $sectionId)->select('employee_id', 'unique_id')->get();
    }

    public function employeeCodeBySection($id)
    {
        return HrEmployeeOfficialInfo::where('section_id', $id)->select('employee_id', 'unique_id', 'code')->get();
    }

    public function employeeNameByCode($id)
    {
        return HrEmployeeOfficialInfo::where('id', $id)
            ->select('employee_id', 'unique_id', 'code', 'employeeBasicInfo.first_name')
            ->get();
    }

    public function employeeGroupInfo($id)
    {
        try {
            $groupId = HrEmployeeOfficialInfo::query()
                ->with('group')
                ->where('employee_id', $id)
                ->select('id', 'employee_id', 'group_id')
                ->first();
            return response()->json($groupId, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function sampleExcelDownload(): BinaryFileResponse
    {
        return response()->download(public_path('/modules/hr/employee_import_sample.xlsx'));
    }

    public function getEmployees()
    {
        try {
            $employees = HrEmployee::query()->get(['id', 'first_name', 'last_name']);
            return response()->json($employees, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function employeeDataExportByExcel()
    {
        return Excel::download(new EmployeeExport(), 'employee-data.xlsx');
    }
}
