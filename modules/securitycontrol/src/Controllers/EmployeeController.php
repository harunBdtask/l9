<?php

namespace SkylarkSoft\GoRMG\SecurityControl\Controllers;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SecurityControl\Models\EmployeeTracking;

class EmployeeController extends Controller
{
    public function index()
    {
        $data['employee_edit'] = null;
        $data['employees'] = EmployeeTracking::orderBy('id', 'desc')->paginate(5);

        return view('securitycontrol::pages.employee_tracking', $data);
    }

    public function store(Request $request, $id = null)
    {
        $request->validate([
            'name' => 'required',
            'accessories_name' => 'required',
            'quantity' => 'required',
            'registration_no' => 'required',

        ]);
        if ($id == null) {
            $employee = new EmployeeTracking();
        } else {
            $employee = EmployeeTracking::find($id);
        }
        $employee->name = $request->name;
        $employee->accessories_name = $request->accessories_name;
        $employee->registration_no = $request->registration_no;
        $employee->quantity = $request->quantity;
        $employee->out_time = now();
        $employee->save();
        if ($id == null) {
            Toastr::success('Employee saved Successfully');
        } else {
            Toastr::success('Employee updated Successfully');
        }

        return redirect()->route('employee.index');
    }

    public function edit($id)
    {
        $data['employee_edit'] = EmployeeTracking::find($id);
        $data['employees'] = EmployeeTracking::orderBy('id', 'desc')->paginate(5);

        return view('securitycontrol::pages.employee_tracking', $data);
    }

    public function delete($id)
    {
        EmployeeTracking::find($id)->delete();
        Toastr::success('Employee deleted Successfully');

        return redirect()->route('employee.index');
    }
}
