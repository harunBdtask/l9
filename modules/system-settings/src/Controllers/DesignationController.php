<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Designation;
use SkylarkSoft\GoRMG\SystemSettings\Requests\DesignationRequest;

class DesignationController extends Controller
{
    public function index()
    {
        $data['designations'] = Designation::orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.designations', $data);
    }

    public function create()
    {
        $data['designation'] = null;

        return view('system-settings::forms.designation', $data);
    }

    public function store(DesignationRequest $request)
    {
        $id = isset($request->id) ? $request->id : '';

        try {
            DB::beginTransaction();
            $designation = Designation::findOrNew($id);
            $designation->designation = $request->designation;
            $designation->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('designations');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['designation'] = Designation::findOrFail($id);

        return view('system-settings::forms.designation', $data);
    }

    public function deleteDesignation($id)
    {
        try {
            DB::beginTransaction();
            $designation = Designation::findOrFail($id);
            $designation->delete();
            DB::commit();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('designations');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }
}
