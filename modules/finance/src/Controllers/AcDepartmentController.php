<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\AcUnit;
use SkylarkSoft\GoRMG\Finance\Models\AcCompany;
use SkylarkSoft\GoRMG\Finance\Models\AcDepartment;
use SkylarkSoft\GoRMG\Finance\Requests\AcDepartmentFormRequest;

class AcDepartmentController extends Controller
{
    public function index()
    {
        $departments = AcDepartment::query()->with('company', 'unit')->orderByDesc('id')->paginate();
        return view('finance::pages.departments', ['departments' => $departments]);
    }

    public function create()
    {
        $data['department'] = null;
        $data['companies'] = AcCompany::query()->with('company', 'unit')->pluck('name', 'id')->all();
        $data['units'] = AcUnit::query()->pluck('unit', 'id')->all();

        return view('finance::forms.department', $data);
    }

    public function store(AcDepartmentFormRequest $request, AcDepartment $ac_department)
    {
        try {
            $ac_department->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('finance/ac-cost-centers');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function edit( $ac_department)
    {
        $data['department'] = AcDepartment::where('id',$ac_department)->first();
        $data['companies'] = AcCompany::query()->pluck('name', 'id')->all();
        $data['units'] = AcUnit::query()->pluck('unit', 'id')->all();

        return view('finance::forms.department', $data);
    }

    public function update(AcDepartmentFormRequest $request, AcDepartment $ac_department)
    {
        try {
            $ac_department->fill($request->all())->save();
            Session::flash('alert-success', 'Data Update Successfully!!');
            return redirect('finance/ac-cost-centers');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function destroy(AcDepartment $ac_department)
    {
        try {
            $ac_department->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');
            return redirect('finance/ac-cost-centers');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!! {$exception->getMessage()}");
            return redirect()->back();
        }
    }
}
