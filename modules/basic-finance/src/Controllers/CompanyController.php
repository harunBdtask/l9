<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use SkylarkSoft\GoRMG\BasicFinance\Models\Company;
use SkylarkSoft\GoRMG\BasicFinance\PackageConst;
use SkylarkSoft\GoRMG\BasicFinance\Rules\UniqueCompanyRule;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class CompanyController extends Controller
{
    public function index()
    {
        $cmp = Company::orderBy('created_at', 'desc')->paginate();
        return view('basic-finance::pages.companies')->with('companies', $cmp);
    }

    public function create()
    {
        $data['company'] = null;
        return view('basic-finance::forms.company', $data);
    }

    public function edit($id)
    {
        $data['company'] = Company::where('id', $id)->first();
        return view('basic-finance::forms.company', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', new UniqueCompanyRule()],
            'group_name' => 'required',
            'corporate_address' => 'required',
            'phone_no' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $company = new Company();
            $company->name = $request->name;
            $company->group_name = $request->group_name;
            $company->corporate_address = $request->corporate_address;
            $company->tin = $request->tin;
            $company->bin = $request->bin;
            $company->country = $request->country;
            $company->phone_no = $request->phone_no;
            $company->email = $request->email;
            $company->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('basic-finance/companies');

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
            $company = Company::findOrFail($id);
            $company->name = $request->name;
            $company->group_name = $request->group_name;
            $company->corporate_address = $request->corporate_address;
            $company->tin = $request->tin;
            $company->bin = $request->bin;
            $company->country = $request->country;
            $company->phone_no = $request->phone_no;
            $company->email = $request->email;
            $company->save();
            DB::commit();
            Session::flash('alert-success', 'Data Updated Successfully!!');
            return redirect('basic-finance/companies');

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
            $company = Company::findOrFail($id);
            $company->delete();
            DB::commit();
            Session::flash('alert-success', 'Data Deleted Successfully!!');
            return redirect('basic-finance/companies');
        } catch(Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!! ERROR CODE: AcCompany.D-102');
            return redirect()->back();
        }
    }
}
