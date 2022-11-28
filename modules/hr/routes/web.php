<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance\AllEmployeesMonthlyAttendanceSummaryController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance\AllEmployeesMonthlyAttendanceSummaryV2Controller;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance\AttendanceProfileController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance\AttendanceReportController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance\ContinuesAbsentReportController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance\DailyRoastingController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance\DailyWorkersAbsentReportController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance\EmployeeJobCardController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance\EmployeeJobCardRegularController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance\ProcessAttendanceController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\AttendanceController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\DepartmentController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\DesignationController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Employee\DisciplineController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Employee\EmployeeStaffController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Employee\TerminationController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\EmployeeAppointmentLetterController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\EmployeeChecklistController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\EmployeeController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\EmployeeDocumentInfoController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\EmployeeEducationInfoController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\EmployeeIdentityCardController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\EmployeeJobExperienceController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\EmployeeOfficialInfoController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\EmployeeSalaryInfoController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\FestivalLeaveController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\GradeController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\GroupDetailsApiController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\GroupsApiController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\HolidayController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Leave\LeaveReportController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\LeaveApplicationController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\LeaveSettingsController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\ManualAttendanceController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\OtApprovalController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\PaymentController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Payroll\BankSalarySheetController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Payroll\PayrollReportController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Payroll\PayslipController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\SalaryHistoryController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\SectionController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Setting\BankController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Setting\GroupController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Setting\ShiftController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\ShiftAPIController;
use SkylarkSoft\GoRMG\HR\Controllers\AttendanceUploadController;
use SkylarkSoft\GoRMG\HR\Controllers\EmployeeUploadController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\ZillaController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\UpazillaController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\PostAddressCodeController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\WorkTypeController;
use SkylarkSoft\GoRMG\HR\Controllers\Api\Setting\HrOfficeTimeSettingController;

Route::group(['prefix' => 'hr', 'middleware' => ['web', 'auth', 'menu-auth']], function () {

    Route::get('employee-list', [EmployeeController::class, 'index']);
    Route::get('employee-staff-list', [EmployeeController::class, 'staffList']);
    Route::get('employee-management-list', [EmployeeController::class, 'managementList']);
    Route::get('employee/salary-history', [EmployeeController::class, 'salaryHistory']);
    Route::get('employee/sample-excel-download', [EmployeeController::class, 'sampleExcelDownload']);
    Route::get('/employee-excel-data-export', [EmployeeController::class, 'employeeDataExportByExcel']);
    Route::post('/employee-information-excel-upload', [EmployeeUploadController::class, 'employeeInformationExcelUpload']);
    Route::view('employee/{any?}', 'hr::employee.create')
        ->where('any', '.*');

    Route::group(['prefix' => 'attendance'], function () {
        Route::get('/dashboard', [AttendanceController::class, 'attendanceDashboard']);
        Route::get('/attendance-by-date', [AttendanceController::class, 'attendanceByDate']);
        Route::get('', [AttendanceController::class, 'attendanceData']);
        Route::get('/absent-list', [AttendanceController::class, 'absentList']);
        Route::get('report/daily-attendence-report', [AttendanceController::class, 'attendanceReport']);
        Route::get('/daily-attendence-report/pdf', [AttendanceController::class, 'attendanceReportPdf']);
        Route::get('/daily-attendence-report/excel', [AttendanceController::class, 'attendanceReportExcel']);
        Route::get('/attendance-list-excel-export', [AttendanceController::class, 'attendanceListExcelExport']);
        Route::get('/attendance-list-sample-excel-download', [AttendanceController::class, 'attendanceListSampleExcelDownload']);
        Route::post('/attendance-list-excel-upload', [AttendanceUploadController::class, 'attendanceListExcelUpload']);
        Route::view('{any?}', 'hr::attendance.attendance')
            ->where('any', '.*');
    });

    Route::get('payroll/{any?}', function () {
        return view('hr::payroll.create');
    })->where('any', '.*');

    Route::view('attendance/{any?}', 'hr::attendance.attendance')
        ->where('any', '.*');

    Route::view('leave/{any?}', 'hr::leave.create')
        ->where('any', '.*');

    Route::group(['prefix' => 'departments'], function () {
        Route::get('/', [DepartmentController::class, 'index']);
        Route::post('/', [DepartmentController::class, 'store']);
        Route::get('/{id}', [DepartmentController::class, 'show']);
        Route::get('/edit/{id}', [DepartmentController::class, 'edit']);
        Route::put('/{id}', [DepartmentController::class, 'update']);
        Route::delete('/{id}', [DepartmentController::class, 'destroy']);
    });

    Route::group(['prefix' => 'sections'], function () {
        Route::get('/', [SectionController::class, 'index']);
        Route::post('/', [SectionController::class, 'store']);
        Route::get('/{id}', [SectionController::class, 'show']);
        Route::get('/edit/{id}', [SectionController::class, 'edit']);
        Route::put('/{id}', [SectionController::class, 'update']);
        Route::delete('/{id}', [SectionController::class, 'destroy']);
    });

    Route::group(['prefix' => 'designations'], function () {
        Route::get('/', [DesignationController::class, 'index']);
        Route::post('/', [DesignationController::class, 'store']);
        Route::get('/{id}', [DesignationController::class, 'show']);
        Route::get('/edit/{id}', [DesignationController::class, 'edit']);
        Route::put('/{id}', [DesignationController::class, 'update']);
        Route::delete('/{id}', [DesignationController::class, 'destroy']);
    });

    Route::group(['prefix' => 'grades'], function () {
        Route::get('/', [GradeController::class, 'index']);
        Route::delete('/{id}', [GradeController::class, 'destroy']);
    });

    Route::resource('leave-settings', LeaveSettingsController::class);

    Route::resource('holidays', HolidayController::class);

    Route::resource('banks', BankController::class);

    Route::resource('shifts', ShiftController::class);

    Route::group(['prefix' => 'festival-leaves'], function () {
        Route::get('/', [FestivalLeaveController::class, 'index']);
        Route::post('/', [FestivalLeaveController::class, 'store']);
        Route::get('/{id}', [FestivalLeaveController::class, 'show']);
        Route::get('/edit/{id}', [FestivalLeaveController::class, 'edit']);
        Route::put('/{id}', [FestivalLeaveController::class, 'update']);
        Route::delete('/{id}', [FestivalLeaveController::class, 'destroy']);
    });

    Route::group(['prefix' => 'office-time-settings'], function () {
        Route::get('/', [HrOfficeTimeSettingController::class, 'index']);
        Route::post('/', [HrOfficeTimeSettingController::class, 'update']);
    });

    Route::group(['prefix' => 'groups',], function () {
        Route::get('', [GroupController::class, 'index']);
        Route::post('', [GroupController::class, 'store']);
        Route::get('edit/{hrGroup}', [GroupController::class, 'edit']);
        Route::put('/{hrGroup}', [GroupController::class, 'update']);
    });

    Route::group(['prefix' => '/api/v1'], function () {
        Route::get('/employee-information-excel-upload', [EmployeeUploadController::class, 'employeeInformationExcelUploadForm']);

        Route::get('employee/staff-list', [EmployeeController::class, 'staffList']);
        Route::get('employee/worker-list', [EmployeeController::class, 'workerList']);
        Route::get('employees-full-list', [EmployeeController::class, 'employeeFullList']);
        Route::get('get-employee-unique-ids', [EmployeeController::class, 'getEmployeeUniqueIds']);
        Route::get('/get-employee-unique-ids-by-department/{deptId}', [EmployeeController::class, 'getEmployeeUniqueIdsByDept']);
        Route::get('/get-employee-unique-ids-by-designation/{designationId}', [EmployeeController::class, 'getEmployeeUniqueIdsByDesignation']);
        Route::get('/get-employees-unique-id-by-section/{sectionId}', [EmployeeController::class, 'getEmployeeUniqueIdsBySection']);
        Route::post('employees', [EmployeeController::class, 'store']);
        Route::get('employees/{id}', [EmployeeController::class, 'show']);
        Route::put('employees/{id}', [EmployeeController::class, 'update']);
        Route::delete('employees/{id}', [EmployeeController::class, 'destroy']);
        Route::get('unique-id-by-section', [EmployeeController::class, 'employeeBySection']);
        Route::get('code-by-section/{id}', [EmployeeController::class, 'employeeCodeBySection']);
        Route::get('employee-name-by-code/{id}', [EmployeeController::class, 'employeeNameByCode']);
        Route::get('employee-for-application', [EmployeeController::class, 'forApplication']);
        Route::get('employee-for-select', [EmployeeController::class, 'employeesForSelect']);
        Route::get('employee-for-daily-roastings', [EmployeeController::class, 'employeesForDailyRoasting']);
        Route::get('employee-group-info/{id}', [EmployeeController::class, 'employeeGroupInfo']);
        Route::get('get-employees', [EmployeeController::class, 'getEmployees']);
        Route::resource('disciplines', DisciplineController::class);
        Route::post('payslips', [PayslipController::class, 'generatePayslip']);
        Route::get('employee-checklists', [EmployeeChecklistController::class, 'index']);
        Route::get('employee-identity-card-generation', [EmployeeIdentityCardController::class, 'generateIdentityCard']);
        Route::get('employees/{id}/official-information', [EmployeeOfficialInfoController::class, 'getOfficialInformation']);
        Route::post('employees/{employeeId}/official-information', [EmployeeOfficialInfoController::class, 'officialInformationStore']);
        Route::get('reporting-to', [EmployeeOfficialInfoController::class, 'reportingTo']);


        Route::get('employees/{employee}/education-information', [EmployeeEducationInfoController::class, 'index']);
        Route::post('employees/{employee}/education-information', [EmployeeEducationInfoController::class, 'store']);


        Route::get('employees/{employee}/job-experience', [EmployeeJobExperienceController::class, 'index']);
        Route::post('employees/{employee}/job-experience', [EmployeeJobExperienceController::class, 'store']);


        Route::get('employees/{employee}/salary-information', [EmployeeSalaryInfoController::class, 'index']);
        Route::post('employees/{employee}/salary-information', [EmployeeSalaryInfoController::class, 'store']);
        Route::get('employees/{employee}/profile', [EmployeeController::class, 'profile']);


        Route::get('employees/{employee}/document', [EmployeeDocumentInfoController::class, 'index']);
        Route::post('employees/{employee}/document', [EmployeeDocumentInfoController::class, 'store']);

        Route::get('employees-search', [EmployeeController::class, 'search']);
        Route::get('employees-list-search', [EmployeeController::class, 'listSearch']);
        Route::get('employee/appointment-letter', EmployeeAppointmentLetterController::class);

        Route::get('departments-list', [DepartmentController::class, 'departmentsList']);

        Route::get('designations-list', [DesignationController::class, 'designationsList']);

        Route::get('sections-list/{department_id}', [SectionController::class, 'sectionsList']);

        Route::get('salary-histories', [SalaryHistoryController::class, 'index']);
        Route::post('salary-histories', [SalaryHistoryController::class, 'store']);
        Route::get('salary-histories/{employee_id}', [SalaryHistoryController::class, 'show']);
        Route::get('salary-histories-by-id/{id}', [SalaryHistoryController::class, 'getById']);
        Route::put('salary-histories/{id}', [SalaryHistoryController::class, 'update']);
        Route::delete('salary-histories/{id}', [SalaryHistoryController::class, 'destroy']);

        Route::post('attendance-check-list', [AttendanceController::class, 'attendanceCheckList']);
        Route::get('make-absent', [AttendanceController::class, 'makeAbsent']);
        Route::get('/get-faiyaz-attendance-data', [AttendanceController::class, 'getFaiyazAttendanceData']);

        Route::get('generate-attendance', [AttendanceController::class, 'generateAttendance']);
        Route::get('generate-holiday-attendance', [AttendanceController::class, 'generateHolidayAttendance']);

        Route::resource('leave-applications', LeaveApplicationController::class)
            ->only('index', 'store', 'show', 'update', 'destroy');
        Route::post('/calculate-leave-end-date', [LeaveApplicationController::class, 'calculateLeaveEndDate']);
        Route::get('/type-based-unique-ids', [LeaveApplicationController::class, 'TypeBasedUniqueIds']);
        Route::get('/uid-based-employee-information', [LeaveApplicationController::class, 'UidBasedEmployeeInformatin']);
        Route::get('/leave-types', [LeaveApplicationController::class, 'LeaveTypes']);
        Route::get('/leave-calculation', [LeaveApplicationController::class, 'LeaveCalculation']);
        Route::post('/leave-application-submit', [LeaveApplicationController::class, 'SubmitApplication']);

        Route::post('delete-leave', [LeaveApplicationController::class, 'deleteLeave']);
        Route::get('/individual-leave-report', [LeaveReportController::class, 'individualLeaveReport']);
        Route::get('/yearly-leave-report', [LeaveReportController::class, 'yearlyLeaveReport']);
        Route::get('/monthly-leave-report', [LeaveReportController::class, 'monthlyLeaveReport']);
        Route::post('/manual-attendance', [ManualAttendanceController::class, 'manualAttendance']);
        Route::post('/manual-attendance-list', [ManualAttendanceController::class, 'manualAttendanceList']);

        Route::get('/ot-approval', [OtApprovalController::class, 'index']);
        Route::post('/ot-approval', [OtApprovalController::class, 'store']);
        Route::get('/get-faiyaz-attendance-data', [AttendanceController::class, 'getFaiyazAttendanceData']);

        Route::post('/generate-monthly-payment-summary', [PaymentController::class, 'generateMonthlyPaymentSummary']);
        Route::get('/generate-monthly-holiday-payment-summary', [PaymentController::class, 'generateMonthlyHolidayPaymentSummary']);
        Route::post('process-attendance', ProcessAttendanceController::class);

        Route::get('/night-ot-list', [AttendanceController::class, 'nightOtList']);

        Route::post('attendance-profile', [AttendanceProfileController::class, '__invoke']);
        Route::post('continues-absent', [ContinuesAbsentReportController::class, '__invoke']);

        Route::post('attendance/employee-job-card-regular', [EmployeeJobCardRegularController::class, '__invoke']);
        Route::post('attendance/employee-job-card-full', [EmployeeJobCardController::class, '__invoke']);
        Route::post('employees-monthly-attendance-summary', [AllEmployeesMonthlyAttendanceSummaryController::class, '__invoke']);
        Route::post('employees-monthly-attendance-summary-v2', [AllEmployeesMonthlyAttendanceSummaryV2Controller::class, '__invoke']);
        Route::get('employees-daily-attendance-summary', [AttendanceReportController::class, 'dailyAttendanceSummary']);
        Route::get('employees-monthly-pay-sheet', [PayrollReportController::class, 'employeesMonthlyPaySheet']);
        Route::get('employees-monthly-extra-ot-sheet', [PayrollReportController::class, 'employeesMonthlyExtraOtSheet']);
        Route::get('employees-monthly-holiday-ot-sheet', [PayrollReportController::class, 'employeesMonthlyHolidayOtSheet']);
        Route::get('total-payment-summary', [PayrollReportController::class, 'totalPaymentSummary']);
        Route::get('daily-workers-absent-report', DailyWorkersAbsentReportController::class);

        Route::get('staffs', [EmployeeStaffController::class, 'index']);
        Route::get('staffs-search', [EmployeeStaffController::class, 'search']);
        Route::resource('terminations', TerminationController::class);
        Route::resource('daily-roastings', DailyRoastingController::class);
        Route::post('bank-salary-sheet', BankSalarySheetController::class);


        Route::get('grades/{groupId}', [GradeController::class, 'grades']);
        Route::post('/grade', [GradeController::class, 'store']);
        Route::get('grade/edit/{id}', [GradeController::class, 'edit']);
        Route::put('grade/{hrGrade}', [GradeController::class, 'update']);
        Route::get('leave-settings', [LeaveSettingsController::class, 'getLeaveSettings']);

        Route::get('shifts', ShiftAPIController::class);
        Route::get('banks', [BankController::class, 'getBanks']);

        Route::get('groups', GroupsApiController::class);
        Route::get('group-details/{hrGroup}', GroupDetailsApiController::class);

        Route::get('/zillas', [ZillaController::class, 'getZillas']);
        Route::get('/upazillas', [UpazillaController::class, 'getUpazillas']);
        Route::get('/upazillas/{zilla_id}', [UpazillaController::class, 'getUpazillasByZillaId']);
        Route::get('/present-address-zillas', [ZillaController::class, 'getZillas']);
        Route::get('/present-address-upazillas/{zilla_id}', [UpazillaController::class, 'getUpazillasByZillaId']);

        Route::get('/post-offices/{zilla_id}', [PostAddressCodeController::class, 'getPostOfficesByZilla']);
        Route::get('/post-codes/{zilla_id}', [PostAddressCodeController::class, 'getPostOfficesByZilla']);
        Route::get('/present-address-post-offices/{zilla_id}', [PostAddressCodeController::class, 'getPostOfficesByZilla']);
        Route::get('/present-address-post-codes/{zilla_id}', [PostAddressCodeController::class, 'getPostOfficesByZilla']);

        Route::get('/work-types', [WorkTypeController::class, 'getWorkTypes']);

    });
});


Route::post('/get-mdb-attendance', [AttendanceController::class, 'getMdbAttendance'])
    ->middleware('web');

Route::get('/get-attendance-data', [AttendanceController::class, 'getAttendanceDataTest'])
    ->middleware('web');
