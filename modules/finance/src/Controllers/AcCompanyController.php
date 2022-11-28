<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SkylarkSoft\GoRMG\Finance\Models\AcCompany;
use SkylarkSoft\GoRMG\Finance\Rules\UniqueAcCompanyRule;

class AcCompanyController extends Controller
{
    public function index()
    {
        $cmp = AcCompany::orderBy('created_at', 'desc')->paginate();
        return view('finance::pages.companies')->with('companies', $cmp);
    }

    public function create()
    {
        $data['company'] = null;
        return view('finance::forms.company', $data);
    }

    public function edit($id)
    {
        $data['company'] = AcCompany::where('id', $id)->first();
        return view('finance::forms.company', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', new UniqueAcCompanyRule()],
            'group_name' => 'required',
            'corporate_address' => 'required',
            'phone_no' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $company = new AcCompany();
            $company->name = $request->name;
            $company->group_name = $request->group_name;
            $company->corporate_address = $request->corporate_address;
            $company->factory_address = $request->factory_address;
            $company->responsible_person = $request->responsible_person;
            $company->tin = $request->tin;
            $company->country = $request->country;
            $company->phone_no = $request->phone_no;
            $company->email = $request->email;
            $company->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('finance/ac-companies');

        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!ERROR CODE CMP.S-101');
            return redirect()->back();
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'group_name' => 'required',
            'corporate_address' => 'required',
            'phone_no' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $company = AcCompany::findOrFail($id);
            $company->name = $request->name;
            $company->group_name = $request->group_name;
            $company->corporate_address = $request->corporate_address;
            $company->factory_address = $request->factory_address;
            $company->responsible_person = $request->responsible_person;
            $company->tin = $request->tin;
            $company->country = $request->country;
            $company->phone_no = $request->phone_no;
            $company->email = $request->email;
            $company->save();
            DB::commit();
            Session::flash('alert-success', 'Data Updated Successfully!!');
            return redirect('finance/ac-companies');

        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!ERROR CODE CMP.S-101');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $company = AcCompany::findOrFail($id);
            $company->delete();
            DB::commit();
            Session::flash('alert-success', 'Data Deleted Successfully!!');
            return redirect('finance/ac-companies');
        } catch(Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!! ERROR CODE: AcCompany.D-102');
            return redirect()->back();
        }
    }
}
