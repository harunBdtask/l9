<?php

namespace SkylarkSoft\GoRMG\HR\Repositories;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SkylarkSoft\GoRMG\HR\Models\HrLeaveApplication;
use SkylarkSoft\GoRMG\HR\Requests\LeaveApplicationRequest;

class LeaveApplicationRepository
{
    public function all()
    {
        $query = HrLeaveApplication::with([
            'employee:id,first_name,last_name,unique_id',
            'department:id,name',
            'section:id,name',
            'designation:id,name'
        ]);

        if ($department = request('departmentId')) {
            $query->where('department_id', $department);
        }

        if ($section = request('sectionId')) {
            $query->where('section_id', $section);
        }

        if ($designation = request('designationId')) {
            $query->where('designation_id', $designation);
        }

        if ($uniqueId = request('uniqueId')) {
            $query->whereHas('employee', function ($q) use ($uniqueId) {
                $q->where('unique_id', $uniqueId);
            });
        }

        return $query->take(50)->orderByDesc('created_at')->get();

    }

    public function paginate()
    {
        return HrLeaveApplication::paginate();
    }


    public function show($id)
    {
        try {
            return HrLeaveApplication::with('details')->findOrFail($id);
        } catch (Exception $e) {
            return false;
        }
    }


    public function store(LeaveApplicationRequest $request)
    {
        try {
            $id = $request->input('id') ?? '';
            $application = HrLeaveApplication::findOrNew($id);
            $application->fill($request->validated());

            DB::beginTransaction();
            $application->save();
            CarbonPeriod::create(
                Carbon::parse($request->input('leave_start')),
                Carbon::parse($request->input('leave_end'))
            )->forEach(function ($date) use ($application, $request) {
                $application->details()->create([
                    'employee_id' => $request->input('employee_id'),
                    'type_id'     => $request->input('type'),
                    'leave_date'  => $date->format('Y-m-d')
                ]);
            });
            DB::commit();;
            return $application;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function update($request)
    {
        return $this->store($request);
    }

    public function destroy($id)
    {
        try {
            HrLeaveApplication::destroy($id);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

}
