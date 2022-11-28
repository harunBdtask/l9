<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricType;
use SkylarkSoft\GoRMG\SystemSettings\Requests\FabricTypeRequest;

class FabricTypeController extends Controller
{
    public function index()
    {
        $data['fabric_types'] = FabricType::orderBy('created_at', 'DESC')
            ->paginate();

        return view('system-settings::pages.fabric-types', $data);
    }

    public function create()
    {
        $data['fabric_type'] = null;

        return view('system-settings::forms.fabric-type', $data);
    }

    public function store(FabricTypeRequest $request)
    {
        $id = isset($request->id) ? $request->id : '';

        try {
            DB::beginTransaction();
            $fabric_type_name = FabricType::findOrNew($id);
            $fabric_type_name->fabric_type_name = $request->fabric_type_name;
            $fabric_type_name->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('fabric-types');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Data Stored Failed!! Error: PT:101');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['fabric_type'] = FabricType::find($id);

        return view('system-settings::forms.fabric-type', $data);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $fabric_type = FabricType::findOrFail($id);
            $fabric_type->delete();
            DB::commit();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('fabric-types');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }
}
