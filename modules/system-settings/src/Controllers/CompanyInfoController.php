<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Exception;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\SystemSettings\Models\Company;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueCompany;

class CompanyInfoController extends Controller
{
    public function index()
    {
        $cmp = Company::query()
            ->orderBy('created_at', 'desc')
            ->paginate();
        $data['company'] = null;

        return view('system-settings::pages.companies', $data)->with('companies', $cmp);
    }

    public function edit($id)
    {
        return Company::findOrFail($id);
    }

    public function store(Request $request)
    {
        $id = $request->get('id') !== null ? $request->get('id') : '';
        $validator = Validator::make($request->all(), [
            'company_name' => ['required', "not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i", new UniqueCompany()],
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $companyFirst = Company::query()->first();
            if (!$companyFirst) {
                $company = new Company();
            } else {
                $company = $companyFirst;
            }
            $company->company_name = $request->get('company_name');
            if ($request->hasFile('company_logo')) {
                if (Company::query()->where('id', $id)->count() > 0) {
                    $file_name_to_delete = Company::query()->where('id', $id)->first()->company_logo;
                    if ($this->hasPrevImg($file_name_to_delete)) {
                        Storage::delete('company/' . $file_name_to_delete);
                    }
                }
                $time = time();
                $file = $request->file('company_logo');
                $file->storeAs('company', $time . $file->getClientOriginalName());
                $company->company_logo = $time . $file->getClientOriginalName();
            }
            $company->save();
            DB::commit();
            if ($request->id) {
                Session::flash('alert-success', 'Data Update Successfully');
            } else {
                Session::flash('alert-success', 'Data Stored Successfully');
            }

            return redirect('companies');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!ERROR CODE CMP.S-101');

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $company = Company::query()->findOrFail($id);
            $file_name_to_delete = $company->company_logo;
            if ($this->hasPrevImg($file_name_to_delete)) {
                Storage::delete('company/' . $file_name_to_delete);
            }
            $company->delete();
            DB::commit();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('companies');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!! ERROR CODE: Company.D-102');

            return redirect()->back();
        }
    }

    /**
     * @param $file_name_to_delete
     * @return bool
     */
    public function hasPrevImg($file_name_to_delete): bool
    {
        return Storage::disk('public')->exists('/company/' . $file_name_to_delete) && $file_name_to_delete != null;
    }
}
