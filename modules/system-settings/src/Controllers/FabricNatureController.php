<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;
use SkylarkSoft\GoRMG\SystemSettings\Requests\FabricNatureRequest;

class FabricNatureController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q ?? '';
        $data['fabric_natures'] = FabricNature::when($q != '', function ($query) use ($q) {
            return $query->where('name', 'like', '%' . $q . '%');
        })->orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.fabric_natures', $data);
    }

    public function create()
    {
        $data['fabric_nature'] = null;

        return view('system-settings::forms.fabric_nature', $data);
    }

    public function store(FabricNatureRequest $request)
    {
        try {
            DB::beginTransaction();
            $fabric_nature = new FabricNature();
            $fabric_nature->name = $request->name;
            $fabric_nature->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('fabric-natures');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function update($id, FabricNatureRequest $request)
    {
        $fabricNatureId = $request->id ?? '';

        try {
            DB::beginTransaction();
            $fabric_nature = FabricNature::findOrNew($fabricNatureId);
            $fabric_nature->name = $request->name;
            $fabric_nature->save();
            DB::commit();
            Session::flash('alert-success', 'Data Updated Successfully!!');

            return redirect('fabric-natures');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['fabric_nature'] = FabricNature::findOrFail($id);

        return view('system-settings::forms.fabric_nature', $data);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $fabric_nature = FabricNature::findOrFail($id);
            $fabric_nature->delete();
            DB::commit();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('fabric-natures');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }
}
