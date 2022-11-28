<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\BasicFinance\Models\Department;
use SkylarkSoft\GoRMG\SystemSettings\Models\BfVariableSetting;
use SkylarkSoft\GoRMG\BasicFinance\Requests\DepartmentFormRequest;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::query()->orderByDesc('id')->paginate();
        return view('basic-finance::pages.departments', ['departments' => $departments]);
    }

    public function create()
    {
        $data['department'] = null;
        $data['users'] = User::get(['first_name', 'last_name','id'])->pluck('full_name', 'id')->prepend('Select User', '');
        $data['variable'] = BfVariableSetting::first();

        return view('basic-finance::forms.department', $data);
    }

    public function store(DepartmentFormRequest $request, Department $department)
    {
        try {
            $department->fill($request->all())->save();
            if($request->get('is_accounting') == 1){
                Department::where('id', '!=', $department->id)->update(['is_accounting' => 0]);
            }
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('basic-finance/departments');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function edit( $department)
    {
        $data['department'] = Department::where('id',$department)->first();
        $data['users'] = User::get(['first_name', 'last_name','id'])->pluck('full_name', 'id')->prepend('Select User', '');
        $data['variable'] = BfVariableSetting::first();

        return view('basic-finance::forms.department', $data);
    }

    public function update(DepartmentFormRequest $request, Department $department)
    {
        try {
            $department->fill($request->all())->save();
            if($request->get('is_accounting') == 1){
                Department::where('id', '!=', $department->id)->update(['is_accounting' => 0]);
            }
            Session::flash('alert-success', 'Data Update Successfully!!');
            return redirect('basic-finance/departments');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function destroy(Department $department)
    {
        try {
            $department->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');
            return redirect('basic-finance/departments');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!! {$exception->getMessage()}");
            return redirect()->back();
        }
    }
}
