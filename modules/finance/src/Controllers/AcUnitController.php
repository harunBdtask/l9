<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\AcCompany;
use SkylarkSoft\GoRMG\Finance\Models\AcUnit;
use SkylarkSoft\GoRMG\Finance\Requests\AcUnitFormRequest;

class AcUnitController extends Controller
{
    public function index()
    {
        $units = AcUnit::query()->with('company')->orderByDesc('id')->paginate();
        return view('finance::pages.units', ['units' => $units]);
    }

    public function create()
    {
        $data['unit'] = null;
        $data['companies'] = AcCompany::query()->pluck('name', 'id')->all();

        return view('finance::forms.unit', $data);
    }

    public function store(AcUnitFormRequest $request, AcUnit $ac_unit)
    {
        try {
            $ac_unit->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('finance/ac-projects');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function edit($ac_unit)
    {
        $data['unit'] = AcUnit::where('id',$ac_unit)->first();
        $data['companies'] = AcCompany::query()->pluck('name', 'id')->all();

        return view('finance::forms.unit', $data);
    }

    public function update(AcUnitFormRequest $request, AcUnit $ac_unit)
    {
        try {
            $ac_unit->fill($request->all())->save();
            Session::flash('alert-success', 'Data Update Successfully!!');
            return redirect('finance/ac-projects');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function destroy(AcUnit $ac_unit)
    {
        try {
            $ac_unit->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');
            return redirect('finance/ac-projects');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!! {$exception->getMessage()}");
            return redirect()->back();
        }
    }
}
