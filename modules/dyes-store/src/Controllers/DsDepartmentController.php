<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\DyesStore\Models\DsDepartment;
use SkylarkSoft\GoRMG\DyesStore\Requests\DsDepartmentRequest;

class DsDepartmentController extends Controller
{
    public function index()
    {
        $departments = DsDepartment::query()->orderBy('id', 'DESC')->paginate();
        return view('dyes-store::pages.departments',[
            'departments' => $departments
        ]);
    }

    public function create()
    {
        return view('dyes-store::forms.department',['department' => null]);
    }

    public function store(DsDepartmentRequest $request, DsDepartment $department)
    {
        try {
            $department->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('/dyes-store/department');
        } catch (Exception $e) {
            Session::flash('alert-danger', "Something went wrong!{$e->getMessage()}");

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $department = DsDepartment::query()->findOrFail($id);
        return view('dyes-store::forms.department', ['department' => $department]);
    }

    public function update(DsDepartmentRequest $request, $id)
    {
        try {
            $department = DsDepartment::query()->where('id',$id)->first();
            $department->update($request->all());
            Session::flash('alert-success', 'Data Updated Successfully!!');

            return redirect('/dyes-store/department');
        } catch (Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function destroy(DsDepartment $department)
    {
        try {
            $department->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');

            return redirect('/dyes-store/department');
        } catch (Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }
}
