<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Models\HrHoliday;
use SkylarkSoft\GoRMG\HR\Models\HrLeaveApplication;
use SkylarkSoft\GoRMG\HR\Models\HrLeaveApplicationDetail;
use SkylarkSoft\GoRMG\HR\Models\HrMonthlyPaymentSummary;
use SkylarkSoft\GoRMG\HR\Repositories\LeaveApplicationRepository;
use SkylarkSoft\GoRMG\HR\Requests\LeaveApplicationRequest;
use SkylarkSoft\GoRMG\HR\Resources\LeaveApplicationResource;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Models\HrLeaveTypes;

class LeaveApplicationController extends Controller
{

    /**
     * @var Repository
     */
    private $repo;

    public function __construct(LeaveApplicationRepository $repository)
    {
        $this->repo = $repository;
    }


    public function index()
    {
        return ApiResponse::create($this->repo->all(), LeaveApplicationResource::class)->getResponse();
    }


    public function store(LeaveApplicationRequest $request)
    {
        return ApiResponse::create($this->repo->store($request), LeaveApplicationResource::class)->getResponse();
    }

    public function show($id)
    {
        return ApiResponse::create($this->repo->show($id), LeaveApplicationResource::class)->getResponse();
    }

    public function update(LeaveApplicationRequest $request, $id)
    {
        return ApiResponse::create($this->repo->update($request), LeaveApplicationResource::class)->getResponse();
    }


    public function destroy($id)
    {
        return ApiResponse::create($this->repo->destroy($id), LeaveApplicationResource::class)->getResponse();
    }

    public function deleteLeave(Request $request)
    {
        $id = $request->input('id');

        try {

            $leave = HrLeaveApplicationDetail::with('employee')->find($id);

            if (!HrMonthlyPaymentSummary::isSummeryGenerated($leave->employee->uniuqe_id, $leave->leave_date)) {

                $isPresent = DB::table('hr_attendance_summaries')->where('userid', $leave->employee->uniuqe_id)
                    ->whereMonth('date', Carbon::parse($leave->leave_date)->format('m'))
                    ->whereYear('date', Carbon::parse($leave->leave_date)->format('Y'))
                    ->count();

                if (!$isPresent) {
                    return response()->json(['success' => false, 'message' => 'Deletion is not possible. Absent!!']);
                }


                DB::beginTransaction();
                HrLeaveApplicationDetail::destroy($id);
                $prevLeaveDate = HrLeaveApplicationDetail::where('id', $id - 1)->where('employee_id', $leave->employee->id)->first();

                if ($prevLeaveDate) {
                    $leave = HrLeaveApplication::find($prevLeaveDate->leave_id);
                    $leave->leave_end = $leave->leave_date;
                    $leave->save();
                }
                DB::commit();

                return response()
                    ->json(['success' => true, 'message' => 'Date removed successfully!']);
            }

            return response()
                ->json(['success' => false, 'message' => 'Payment summery generated. Deletion is not possible!']);

        } catch (Exception $e) {
            DB::rollBack();
            return response()
                ->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function calculateLeaveEndDate(Request $request)
    {
        return Carbon::create($request->input('start_date'))->addDays($request->input('day_count'))->format('Y-m-d');
    }
    public function TypeBasedUniqueIds(Request $request){
        $type = $request['selected_type'];
        $unique_ids = [];
        $uids = HrEmployeeOfficialInfo::orderBy('id','asc')->when($type, function ($query, $type) {
            $query->where('type', $type)->select('unique_id');
        })->get();
        foreach($uids as $uid){
            $unique_ids[] = $uid['unique_id'];
        }
        return response()->json(['status' => 'success', 'data' => $unique_ids, 'code' => 200]);
    }

    public function UidBasedEmployeeInformatin(Request $request){
        $employees = HrEmployeeOfficialInfo::query()
                    ->when($request->get('unique_id'),function($query) use($request) {
                        return $query->where('unique_id',$request->get('unique_id'));
                    })
                    ->with([
                        'employeeBasicInfo:id,first_name',
                        'departmentDetails:id,name',
                        'sectionDetails:id,name',
                        'designationDetails:id,name',
                    ])
                    ->get()
                    ->map(function ($collection){
                        return [
                            'unique_id' => $collection->unique_id,
                            'employee_id' => $collection->employee_id,
                            'department_id' => $collection->department_id,
                            'designation_id'=> $collection->designation_id,
                            'punch_card_id' => $collection->punch_card_id,
                            'section_id' => $collection->section_id,
                            'type'=> $collection->type,
                            'employee_basic_info'=> $collection->employeeBasicInfo,
                            'department_details'=> $collection->departmentDetails,
                            'section_details'=> $collection->sectionDetails,
                            'designation_details'=> $collection->designationDetails,
                        ];
                    });
        return response()->json(['status' => 'success', 'data' => $employees, 'code' => 200]);
    }

    public function SubmitApplication(Request $request){
        $start_date = $request['leave_start'];
        try {
                DB::beginTransaction();
                $typeBasedLeaves = $request->type_based_leaves;
                foreach ($typeBasedLeaves as $typeBasedLeave) {
                    if($typeBasedLeave["number_of_days"]>0){
                        $application = new HrLeaveApplication();
                        $typeBasedLeave = gettype($typeBasedLeave) == 'string' ? json_decode($typeBasedLeave, true) : $typeBasedLeave;
                        $application->department_id = $request["department_id"];
                        $application->employee_id = $request["employee_id"];
                        $application->unique_id = $request["unique_id"];
                        $application->section_id = $request["section_id"];
                        $application->designation_id = $request["designation_id"];
                        $application->applicant_name = $request["applicant_name"];
                        $application->reason = $request["reason"];
                        $application->application_date = $request["application_date"];
                        $application->leave_start = $start_date;
                        $end_date = $this->startEndDateCount($start_date, $typeBasedLeave["number_of_days"]);
                        $application->leave_end = $end_date;
                        $application->rejoin_date = $request["rejoin_date"];
                        $application->contact_details = $request["contact_details"];
                        $application->application_for = $request["application_for"];
                        $application->is_approved = $request["is_approved"];
                        $application->type = $typeBasedLeave["type"];
                        $application->total_days = $typeBasedLeave["number_of_days"];
                        $application->save();
                        $periodDates = CarbonPeriod::create($start_date, $end_date);
                        foreach ($periodDates as $periodDate) {
                            $leave_date = $periodDate;
                            $skipOrNot = $this->checkSkip($leave_date);
                            if($skipOrNot == 'holiday'){
                                continue;
                            }
                            $leaveApplicationDetails = new HrLeaveApplicationDetail();
                            $leaveApplicationDetails->employee_id = $application->employee_id;
                            $leaveApplicationDetails->leave_id = $application->id;
                            $leaveApplicationDetails->type_id = $application->type;
                            $leaveApplicationDetails->leave_date = $leave_date;
                            $leaveApplicationDetails->factory_id = $application->factory_id;
                            $leaveApplicationDetails->save();
                        }
                        $start_date = Carbon::create($end_date)->addDays(+1)->format('Y-m-d');
                        $skipOrNot = $this->checkSkip($start_date);
                        if($skipOrNot == 'holiday'){
                            do{
                                $start_date = Carbon::create($start_date)->addDays(+1)->format('Y-m-d');
                                $skipOrNot = $this->checkSkip($start_date);
                            }while($skipOrNot == 'holiday');
                        }
                    }
                    else{
                        return response()
                            ->json(['success' => false, 'message' => 'Invalid Input', 'code' => 422]);
                    }
                }
                DB::commit();
                return response()
                    ->json(['success' => true, 'message' => 'Application submitted successfully!', 'code' => 200]);
            }
             catch (Exception $e) {
                DB::rollBack();
                return response()
                    ->json(['success' => false, 'message' => $e->getMessage(), 'code' => 422]);
            }
    }
    public function LeaveTypes(Request $request){
        $employee_type = $request->selected_type;
        $data = [];
        $Leave_types = HrLeaveTypes::query()
                     ->with([
                         'employeeTypes' => function ($query) use ($employee_type) {
                             return $query->where('employee_type', $employee_type);
                         }])
                     ->get()
                     ->map(function ($collection){
                         $leave_available = collect($collection->employeeTypes)->count() > 0 ? 'Present' : 'Absent';
                         $total_leave = collect($collection->employeeTypes)->count() > 0 ? collect($collection->employeeTypes)->first()['number_of_days']: null;
                         return[
                             'id' => $collection->id,
                             'type' => $collection->name,
                             'total_leave' => $total_leave,
                             'available' => $leave_available
                         ];
                     });
        foreach ($Leave_types as $Leave_type) {
           if ($Leave_type['available'] == 'Present') {
               $data [] = $Leave_type;
           }
        }
       return response()->json(['status' => 'success', 'data' => $data, 'code' => 200]);
   }
    public function LeaveCalculation(Request $request){
        $type = $request['type'];
        $unique_id = $request['unique_id'];
        $taken_leaves = HrLeaveApplication::query()
            ->where('type',$type)
            ->whereYear('leave_start', date('Y'))
            ->where('is_approved','yes')
            ->where('unique_id', $unique_id)
            ->get();
        $taken_leave_count = 0;
        foreach($taken_leaves as $taken_leave){
            $taken_leave_count = $taken_leave['total_days']+ $taken_leave_count;
        }
        return response()->json(['status' => 'success', 'data' => $taken_leave_count, 'code' => 200]);
   }
    private function checkSkip($selectedDate): string
    {
//        $date = Carbon::parse($selectedDate);
        $fridayFlag = 0;
        $holidayFlag = HrHoliday::whereDate('date', $selectedDate)->get()->count();
        $fridayFlagDate = Carbon::createFromDate($selectedDate);
        if($fridayFlagDate->dayOfWeek  == Carbon::FRIDAY){
            $fridayFlag = 1;
        }
        if ($holidayFlag>0 || $fridayFlag>0){
            return 'holiday';
        }
        return  'workingDay';

    }
    public function startEndDateCount($startEndDate, $totalDate): string
    {
        $finalDate = $startEndDate;
        for( $i = 0; $i<$totalDate; $i++){
            if($i>0)
            {
                $finalDate = Carbon::create($finalDate)->addDays(+1)->format('Y-m-d');
            }
            $skipOrNot = $this->checkSkip($finalDate);
            if($skipOrNot == 'workingDay'){
                continue;
            }
            do{
                $finalDate = Carbon::create($finalDate)->addDays(+1)->format('Y-m-d');
                $skipOrNot = $this->checkSkip($finalDate);
            }while($skipOrNot == "holiday");
        }
        return $finalDate;
    }

}
