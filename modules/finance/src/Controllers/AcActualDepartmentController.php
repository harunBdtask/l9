<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\AcUnit;
use SkylarkSoft\GoRMG\Finance\Models\AcCompany;
use SkylarkSoft\GoRMG\Finance\Models\AcDepartment;
use SkylarkSoft\GoRMG\Finance\Models\AcActualDepartment;
use SkylarkSoft\GoRMG\Finance\Requests\AcActualDepartmentFormRequest;

class AcActualDepartmentController extends Controller
{
    public function index()
    {
        $actualDepartments = AcActualDepartment::query()->with(['company', 'unit', 'department'])->orderByDesc('id')->paginate();
        return view('finance::pages.actual-departments', ['actualDepartments' => $actualDepartments]);
    }

    public function create()
    {
        $data['actualDepartment'] = null;
        $data['companies'] = AcCompany::query()->with(['company', 'unit', 'department'])->pluck('name', 'id')->all();
        $data['units'] = AcUnit::query()->with( 'unit', 'department')->pluck('unit', 'id')->all();
        $data['departments'] = AcDepartment::query()->pluck('name', 'id')->all();

        return view('finance::forms.actual-department', $data);
    }

    public function store(AcActualDepartmentFormRequest $request, AcActualDepartment $ac_actual_department)
    {
        try {
            $ac_actual_department->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('finance/ac-departments');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function edit(AcActualDepartment $ac_actual_department)
    {
        $data['actualDepartment'] = AcActualDepartment::where('id',$ac_actual_department)->first();
        $data['companies'] = AcCompany::query()->pluck('name', 'id')->all();
        $data['units'] = AcUnit::query()->pluck('unit', 'id')->all();
        $data['departments'] = AcDepartment::query()->pluck('name', 'id')->all();

        return view('finance::forms.actual-department', $data);
    }

    public function update(AcActualDepartmentFormRequest $request, AcActualDepartment $ac_actual_department)
    {
        try {
            $ac_actual_department->fill($request->all())->save();
            Session::flash('alert-success', 'Data Update Successfully!!');
            return redirect('finance/ac-departments');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function destroy(AcActualDepartment $ac_actual_department)
    {
        try {
            $ac_actual_department->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');
            return redirect('finance/ac-departments');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!! {$exception->getMessage()}");
            return redirect()->back();
        }
    }
}
