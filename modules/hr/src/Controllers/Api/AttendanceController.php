<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DB, Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Exports\AttendanceExport;
use SkylarkSoft\GoRMG\HR\Imports\AttendanceImport;
use SkylarkSoft\GoRMG\HR\Jobs\GenerateAttendanceSummaryJob;
use SkylarkSoft\GoRMG\HR\Models\HrAttendance;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceRawData;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Repositories\AttendanceRepository;
use SkylarkSoft\GoRMG\HR\Repositories\DepartmentRepository;
use SkylarkSoft\GoRMG\HR\Repositories\DesignationRepository;
use SkylarkSoft\GoRMG\HR\Repositories\NightOtRepository;
use SkylarkSoft\GoRMG\HR\Repositories\SectionRepository;
use SkylarkSoft\GoRMG\HR\Resources\AttendanceResource;
use SkylarkSoft\GoRMG\HR\Resources\NightOtResource;
use SkylarkSoft\GoRMG\HR\Services\AttendanceService;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\HR\Exports\DailyAttendenceReportExcel;
use Throwable;
use Illuminate\Support\Arr;
use function Pest\Laravel\json;

class AttendanceController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function getMdbAttendance(Request $request)
    {
        try {
            DB::beginTransaction();
            $formattedAttendanceData = [];
            $attendanceDate = "";
            foreach ($request->all() as $attendanceData) {

                $punchTime = date("H:i:s", strtotime($attendanceData['CHECKTIME']));
                $attendanceDate = date("Y-m-d", strtotime($attendanceData['CHECKTIME']));

                $checkDataExist = HrAttendanceRawData::query()->where([
                    'userid' => $attendanceData['Badgenumber'],
                    'punch_time' => $punchTime,
                    'attendance_date' => $attendanceDate,
                ])->exists();

                if ($checkDataExist) {
                    continue;
                }

                $formattedAttendanceData[] = [
                    'userid' => $attendanceData['Badgenumber'],
                    'punch_time' => $punchTime,
                    'attendance_date' => $attendanceDate,
                    'flag' => 'A',
                    'machine_data' => json_encode($attendanceData),
                    'factory_id' => factoryId(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            HrAttendanceRawData::query()->insert($formattedAttendanceData);
            DB::commit();
            GenerateAttendanceSummaryJob::dispatch($attendanceDate);
            return response()->json(['message' => 'imported successfully'], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    public function getFaiyazAttendanceData(Request $request)
    {
        $dateFrom = $request->date_from ?? date('Y-m-d', strtotime("-3 days"));
        $dateTo = $request->date_to ?? date('Y-m-d');


        $machine_attendance_data_update = HrAttendance::query()
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo)
            ->orderBy('date')
            ->chunk(500, function ($attendance_data) {
                $this->updateMachineAttendanceData($attendance_data);
            });

        $raw_attendance_data_update = DB::table('auditdata')
            ->whereDate('AttendDate', '>=', $dateFrom)
            ->whereDate('AttendDate', '<=', $dateTo)
            ->orderBy('AttendDate')
            ->chunk(500, function ($raw_attendance_data) {
                $this->updateRawAttendanceData($raw_attendance_data);
            });

        return 'success';
    }

    public function updateMachineAttendanceData($attendance_data)
    {
        try {
            DB::beginTransaction();
            foreach ($attendance_data as $data) {
                $machine_attendances = HrAttendance::firstOrNew([
                    'userid' => $data->userid,
                    'date' => $data->date,
                ]);
                $machine_attendances->idattendance_sheet = $data->idattendance_sheet;
                $machine_attendances->userid = $data->userid;
                $machine_attendances->date = $data->date;
                $machine_attendances->att_in = $data->att_in;
                $machine_attendances->att_break = $data->att_break;
                $machine_attendances->att_resume = $data->att_resume;
                $machine_attendances->att_out = $data->att_out;
                $machine_attendances->att_ot = $data->att_ot;
                $machine_attendances->att_done = $data->att_done;
                $machine_attendances->workhour = $data->workhour;
                $machine_attendances->othour = $data->othour;
                $machine_attendances->save();
            }
            DB::commit();

            return [
                'status' => true,
                'error' => null,
            ];
        } catch (Exception $e) {
            DB::rollback();
            return [
                'status' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function updateRawAttendanceData($raw_attendance_data)
    {
        DB::beginTransaction();
        foreach ($raw_attendance_data as $data) {
            $isExists = HrAttendanceRawData::where([
                'userid' => $data->userid,
                'attendance_date' => $data->AttendDate,
                'punch_time' => $data->checktime,
            ])->first();
            if ($isExists) {
                continue;
            }
            $collection = new HrAttendanceRawData();
            $collection->userid = $data->userid;
            $collection->punch_time = $data->checktime;
            $collection->attendance_date = $data->AttendDate;
            $collection->created_by = null;
            $collection->save();
        }
        DB::commit();
    }

    public function attendanceList(Request $request)
    {
        $departments = (new DepartmentRepository())->all();
        $designations = (new DesignationRepository())->all();
        $sections = (new SectionRepository())->all();
//        dd($sections);
        $types = [
            [
                'id' => 'worker',
                'name' => 'Worker',
            ],
            [
                'id' => 'staff',
                'name' => 'Staff'
            ]
        ];

        $attendanceRepository = new AttendanceRepository();
        $attendances = collect($attendanceRepository->attendanceList($request))
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'userid' => $data->userid,
                    'date' => $data->date ? date('d/m/Y', strtotime($data->date)) : '',
                    'raw_date' => $data->date,
                    'name' => $data->name,
                    'att_in' => $data->att_in,
                    'att_break' => $data->att_break,
                    'att_resume' => $data->att_resume,
                    'att_out' => $data->att_out,
                    'att_ot' => $data->att_ot,
                    'att_done' => $data->att_done,
                    'workhour' => $data->workhour,
                    'othour' => $data->othour,
                    'screen_name' => $data->employeeOfficialInfo->employeeBasicInfo->screen_name,
                    'department' => $data->employeeOfficialInfo->departmentDetails->name,
                    'designation' => $data->employeeOfficialInfo->designationDetails->name,
                    'section' => $data->employeeOfficialInfo->sectionDetails->name,
                    'type' => $data->employeeOfficialInfo->type,
                    'code' => $data->employeeOfficialInfo->code,
                ];
            });
        return view('hr::attendance.attendance-list',
            [
                'departments' => $departments,
                'designations' => $designations,
                'sections' => $sections,
                'types' => $types,
                'attendances' => $attendances
            ]);
    }

    public function attendanceCheckList(Request $request)
    {
        $attendanceRepository = new AttendanceRepository();
        $data['reports'] = $attendanceRepository->attendanceCheckList($request);
        $data['departmentId'] = $request->department_id;
        $data['designationId'] = $request->designation_id;
        $data['sectionId'] = $request->section_id;
        $data['type'] = $request->type;
        $data['date'] = $request->date ?? date('Y-m-d');

        $view = view('hr::employee.attendance_checklist', $data)->render();
        return response()->json([
            'view' => $view
        ]);
    }

    public function generateAttendance(Request $request)
    {
        return (new AttendanceRepository())->processRegularAttendance($request);
    }

    public function generateHolidayAttendance(Request $request)
    {
        return (new AttendanceRepository())->processHolidayAttendance($request);
    }

    public function nightOtList(Request $request)
    {
        $nightOtRepo = new NightOtRepository;
        $apiResponse = new ApiResponse($nightOtRepo->paginate(), NightOtResource::class);
        return $apiResponse->getResponse();
    }

    public function absentList(Request $request)
    {
        $departments = (new DepartmentRepository())->all();
        $designations = (new DesignationRepository())->all();
        $sections = (new SectionRepository())->all();
        $types = [
            [
                'id' => 'worker',
                'name' => 'Worker',
            ],
            [
                'id' => 'staff',
                'name' => 'Staff'
            ],
            [
                'id' => 'management',
                'name' => 'Management',
            ],
        ];

        $searchParams = $request->all();
        $date = 0;
        $uids = HrEmployeeOfficialInfo::select('unique_id')->get();
        $unique_id = $request['unique_id'];
        $type = $request['type'];
        $date = $request->get('date', Carbon::now()->toDateString());
        $absent_employees = [];

        $employees = HrEmployeeOfficialInfo::query()
            ->when($request->get('type'), function ($query) use ($request) {
                return $query->where('type', $request->get('type'));
            })
            ->when($request->get('unique_id'), function ($query) use ($request) {
                return $query->where('unique_id', $request->get('unique_id'));
            })
            ->with([
                'employeeBasicInfo:id,first_name',
                'departmentDetails:id,name',
                'sectionDetails:id,name',
                'designationDetails:id,name',
                'attendances' => function ($query) use ($date) {
                    return $query->where('attendance_date', $date)->orderBy('punch_time', 'ASC');
                },
                'last_attendence_dates' => function ($query) use ($date) {
                    return $query->where('attendance_date', '<', $date)->orderBy('attendance_date', 'DESC');
                }
            ])
            ->get()
            ->map(function ($collection) {
                $attendanceStatus = collect($collection->attendances)->count() > 0 ? 'Present' : 'Absent';
                $last_attendence = collect($collection->last_attendence_dates)->count() > 0 ? $collection->last_attendence_dates[0] : null;
                return [
                    'unique_id' => $collection->unique_id,
                    'department_id' => $collection->department_id,
                    'designation_id' => $collection->designation_id,
                    'punch_card_id' => $collection->punch_card_id,
                    'section_id' => $collection->section_id,
                    'type' => $collection->type,
                    'employee_basic_info' => $collection->employeeBasicInfo,
                    'department_details' => $collection->departmentDetails,
                    'section_details' => $collection->sectionDetails,
                    'designation_details' => $collection->designationDetails,
                    'attendance_status' => $attendanceStatus,
                    'last_attendance_date' => $last_attendence,
                ];
            });
        foreach ($employees as $employee) {
            if ($employee['attendance_status'] == 'Absent') {
                $absent_employees [] = $employee;
            }
        }
        return view('hr::attendance.absent-list',
            compact(
                'absent_employees',
                'date',
                'uids',
                'types',
                'unique_id',
                'type'
            )
        );
    }


    public function attendanceReport(Request $request)
    {
        $departments = (new DepartmentRepository())->all();
        $designations = (new DesignationRepository())->all();
        $sections = (new SectionRepository())->all();
        $uids = HrEmployeeOfficialInfo::select('unique_id')->get();
        $present_count = 0;
        $absent_count = 0;
        $late_count = 0;
        $types = [
            [
                'id' => 'worker',
                'name' => 'Worker',
            ],
            [
                'id' => 'staff',
                'name' => 'Staff'
            ],
            [
                'id' => 'management',
                'name' => 'Management',
            ],
        ];
        $date = $request->get('date', Carbon::now()->toDateString());

        $employees = $this->attendenceReportData($date, $request);
        foreach ($employees as $employee) {
            if (!empty($employee['first_punch_time_in_day'])) {
                $present_count++;
            } else {
                $absent_count++;
            }
            if ($employee['late_status'] == 'Yes') {
                $late_count++;
            }
        }
        return view('hr::reports.daily-attendence-report.index', compact('employees', 'date', 'types', 'uids', 'present_count', 'absent_count', 'late_count'));
    }

    public function isLateValidation($entryTime, $userType)
    {
        if ($userType) {
            $type = $userType;
            $officeTime = '08:15';
            if ($type == 'worker') {
                $officeTime = '08:05:00';
            }
            if (Carbon::parse($officeTime)->lt(Carbon::parse($entryTime)->format('H:i:s'))) {
                return 'Yes';
            } else {
                return 'No';
            }
        }
    }

    public function makeAbsent(Request $request)
    {
        HrAttendance::where([
            'date' => $request->date,
            'userid' => $request->userid,
        ])->update([
            'manual_absent_status' => 1
        ]);
        (new AttendanceRepository())->processRegularAttendance($request);
        return redirect()->back();
    }

    public function attendanceData(Request $request)
    {
        $departments = (new DepartmentRepository())->all();
        $designations = (new DesignationRepository())->all();
        $sections = (new SectionRepository())->all();
        $types = [
            [
                'id' => 'worker',
                'name' => 'Worker',
            ],
            [
                'id' => 'staff',
                'name' => 'Staff'
            ],
            [
                'id' => 'management',
                'name' => 'Management',
            ],
        ];

        if ($request->get('date')) {
            $attendances = $this->searchAttendanceData($request);
        } else {
            $attendances = HrAttendanceRawData::query()
                ->with([
                    'employeeOfficialInfo.employeeBasicInfo:id,first_name,last_name',
                    'employeeOfficialInfo.departmentDetails',
                    'employeeOfficialInfo.designationDetails',
                    'employeeOfficialInfo.sectionDetails',
                ])->whereDate('attendance_date', date('Y-m-d'))->get();
        }

        return view('hr::attendance.attendance-list',
            [
                'departments' => $departments,
                'designations' => $designations,
                'sections' => $sections,
                'types' => $types,
                'attendances' => $attendances
            ]);
    }

    public function getAttendanceDataTest()
    {
        $hrAttendanceData = HrAttendanceRawData::query()->with(['employeeOfficialInfo.employeeBasicInfo'])->get();
        return view('hr::attendance.attendance-data', ['attendanceData' => $hrAttendanceData]);
    }

    public function searchAttendanceData(Request $request)
    {
        $department = $request->get('department');
        $designation = $request->get('designation');
        $section = $request->get('section');
        $type = $request->get('type');
        $date = $request->get('date');

        return HrAttendanceRawData::query()->with([
            'employeeOfficialInfo.departmentDetails',
            'employeeOfficialInfo.designationDetails',
            'employeeOfficialInfo.sectionDetails',
        ])
            ->whereHas('employeeOfficialInfo', function ($q) use ($department, $designation, $section, $type, $date) {
                $q->when($department, function ($query) use ($department) {
                    $query->where('department_id', $department);
                })->when($designation, function ($query) use ($designation) {
                    $query->where('designation_id', $designation);
                })->when($section, function ($query) use ($section) {
                    $query->where('section_id', $section);
                })->when($type, function ($query) use ($type) {
                    $query->where('type', $type);
                });
            })->where('attendance_date', $date)->get();
    }

    public function attendanceDashboard()
    {
        return view('hr::attendance.attendance-dashboard');
    }

    public function attendanceByDate()
    {
        $dateToday = date('Y-m-d');
        $punch_card_ids = HrEmployeeOfficialInfo::query()->pluck('punch_card_id')->values();

        $attendance = HrAttendanceSummary::query();
        $presentEmployee = $attendance->whereIn('userid', $punch_card_ids)
            ->whereDate('date', '=', $dateToday)->count();

        $lateEmployee = $attendance->whereDate('date', '=', $dateToday)
            ->where('status', 'Late')->count();
        $absentEmployee = (collect($punch_card_ids)->count() - $presentEmployee);

        return response()->json(
            [
                'presentEmployee' => $presentEmployee,
                'lateEmployee' => $lateEmployee,
                'absentEmployee' => $absentEmployee
            ], Response::HTTP_OK);
    }

    public function attendanceListSampleExcelDownload()
    {
        return response()->download(public_path('/modules/hr/attendance_list_import_sample.xlsx'));
    }

    public function attendanceListExcelExport()
    {

        return Excel::download(new AttendanceExport, 'attendance-data.xlsx');

    }

    public function attendanceReportPdf(Request $request)
    {
        $date = $request->get('date', Carbon::now()->toDateString());
        $employees = $this->attendenceReportData($date, $request);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('hr::reports.daily-attendence-report.pdf', compact('employees', 'date'))
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->download('daily-attendence-report' . '.pdf');
    }

    public function attendanceReportExcel(Request $request)
    {
        $date = $request->get('date', Carbon::now()->toDateString());
        $employees = $this->attendenceReportData($date, $request);
        return Excel::download(new DailyAttendenceReportExcel(compact('employees','date')), 'daily-attendence-report-' . $date . '.xlsx');
    }

    public function attendenceReportData($date, $request)
    {
        $employees = HrEmployeeOfficialInfo::query()
            ->when($request->get('type'), function ($query) use ($request) {
                return $query->where('type', $request->get('type'));
            })
            ->when($request->get('unique_id'), function ($query) use ($request) {
                return $query->where('unique_id', $request->get('unique_id'));
            })
            ->with([
                'employeeBasicInfo:id,first_name',
                'departmentDetails:id,name',
                'sectionDetails:id,name',
                'designationDetails:id,name',
                'attendances' => function ($query) use ($date) {
                    return $query->where('attendance_date', $date)->orderBy('punch_time', 'ASC');
                }])
            ->get()
            ->map(function ($collection) {
                $attendanceStatus = collect($collection->attendances)->count() > 0 ? 'Present' : 'Absent';
                $firstPunchTimeInDay = collect($collection->attendances)->first()['punch_time'] ?? null;
                $late_status = collect($collection->attendances)->count() > 0 ? $this->isLateValidation(collect($collection->attendances)->first()['punch_time'], $collection->type) : '';
                return [
                    'unique_id' => $collection->unique_id,
                    'department_id' => $collection->department_id,
                    'designation_id' => $collection->designation_id,
                    'punch_card_id' => $collection->punch_card_id,
                    'section_id' => $collection->section_id,
                    'type' => $collection->type,
                    'employee_basic_info' => $collection->employeeBasicInfo,
                    'department_details' => $collection->departmentDetails,
                    'section_details' => $collection->sectionDetails,
                    'designation_details' => $collection->designationDetails,
                    'attendance_status' => $attendanceStatus,
                    'first_punch_time_in_day' => $firstPunchTimeInDay,
                    'late_status' => $late_status
                ];
            });
        return $employees;
    }
}
