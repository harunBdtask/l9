<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\Models\HrTermination;

class TerminationController extends Controller
{
    public function store(Request $request)
    {
        $this->validateRequest($request);
        try {
            $termination = HrTermination::firstOrNew(['employee_id' => $request->input('employee_id')]);
            $termination->fill($this->getData());
            $termination->save();
            return response()->json(['success' => true, 'data' => $termination]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }

    public function show($employeeId)
    {
        $termination = HrTermination::where('employee_id', $employeeId)->first();
        return response()->json(['data' => $termination]);
    }

    public function update(HrTermination $termination, Request $request)
    {
        $this->validateRequest($request);
        $termination->update($this->getData());
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'employee_id'        => 'required',
            'termination_date'   => 'required',
            'termination_reason' => 'required',
            'last_working_day'   => 'required'
        ]);
    }

    private function getData()
    {
        return request()->all(
            'employee_id',
            'termination_date',
            'termination_reason',
            'last_working_day'
        );
    }
}
