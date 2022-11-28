<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Department;
use SkylarkSoft\GoRMG\SystemSettings\Requests\DepartmentRequest;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('id', 'DESC')->paginate(10);

        return view('system-settings::pages.departments', ['departments' => $departments, 'department' => null]);
    }

    public function store(DepartmentRequest $request)
    {
        if (Department::create($request->all())) {
            Session::flash('success', 'Data stored successfully');
        } else {
            Session::flash('error', 'Data stored failed!!');
        }

        return redirect('/departments');
    }

    public function edit($id)
    {
        return Department::findOrFail($id);
    }

    public function update($id, DepartmentRequest $request)
    {
        $department = Department::findOrFail($id);
        $department->update($request->all());

        Session::flash('success', 'Data updated successfully');

        return redirect('/departments');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        Session::flash('success', 'Data deleted successfully!!');

        return redirect('/departments');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $departments = Department::where('department_name', 'like', '%' . $search . '%')->orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.departments', ['departments' => $departments, 'department' => null, 'search' => $search]);
    }
}
