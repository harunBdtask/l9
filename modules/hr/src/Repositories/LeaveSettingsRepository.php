<?php

namespace SkylarkSoft\GoRMG\HR\Repositories;

use Exception;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\Models\HrLeaveSetting;
use SkylarkSoft\GoRMG\HR\Requests\LeaveSettingRequest;

class LeaveSettingsRepository
{
    public function all()
    {
        return HrLeaveSetting::all();
    }

    public function paginate()
    {
        return HrLeaveSetting::query()->with('leaveType')->paginate();
    }

    public function store(LeaveSettingRequest $request)
    {
        try {
            $employee_types = $request->input('employee_type');
            $leave_type_id = $request->input('leave_type_id');
            $number_of_days = $request->input('number_of_days');

            if (count($employee_types) > 1 ) {
                foreach ($employee_types as $employee_type) {
                    HrLeaveSetting::create([
                        'employee_type' => $employee_type,
                        'leave_types_id' => $leave_type_id,
                        'number_of_days' => $number_of_days
                    ]);
                }
            } else {
                $employee_type = implode(' ', $employee_types);

                return HrLeaveSetting::create([
                    'employee_type' => $employee_type,
                    'leave_types_id' => $leave_type_id,
                    'number_of_days' => $number_of_days
                ]);
            }

        } catch (\Exception $exception) {
            return false;
        }
    }

    public function show($id)
    {
        return HrLeaveSetting::find($id);
    }

    public function update(LeaveSettingRequest $request, $id)
    {
        try {
            $leaveSetting = HrLeaveSetting::findOrFail($id);
            $employee_type = implode(' ', $request->input('employee_type'));
            $leaveSetting->update([
                'employee_type' => $employee_type,
                'leave_types_id' => $request->input('leave_type_id'),
                'number_of_days' => $request->input('number_of_days')
            ]);
            return $leaveSetting;
        } catch (Exception $e) {
            return false;
        }
    }

    public function destroy($id)
    {
        try {
            HrLeaveSetting::destroy($id);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
