<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricCompositionDetail;
use SkylarkSoft\GoRMG\SystemSettings\Requests\CompositionTypeRequest;

class CompositionTypeController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q ?? '';
        $data['composition_types'] = CompositionType::when($q != '', function ($query) use ($q) {
            return $query->where('name', 'like', '%' . $q . '%');
        })->orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.composition_types', $data);
    }

    public function create()
    {
        $data['composition_type'] = null;

        return view('system-settings::forms.composition_type', $data);
    }

    public function store(CompositionTypeRequest $request)
    {
        try {
            DB::beginTransaction();
            $composition_type = new CompositionType();
            $composition_type->name = $request->name;
            $composition_type->save();
            DB::commit();

            if (Request::capture()->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $composition_type
                ]);
            }

            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('composition-types');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function update($id, CompositionTypeRequest $request)
    {
        $id = isset($request->id) ? $request->id : '';

        try {
            DB::beginTransaction();
            $composition_type = CompositionType::findOrNew($id);
            $composition_type->name = $request->name;
            $composition_type->save();
            DB::commit();
            Session::flash('alert-success', 'Data Updated Successfully!!');

            return redirect('composition-types');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['composition_type'] = CompositionType::findOrFail($id);

        return view('system-settings::forms.composition_type', $data);
    }

    public function destroy($id)
    {
        $fabricCompositionDetails = NewFabricCompositionDetail::where('composition_type_id', $id)->first();

        if (! isset($fabricCompositionDetails)) {
            try {
                DB::beginTransaction();
                $composition_type = CompositionType::findOrFail($id);
                $composition_type->delete();
                DB::commit();
                Session::flash('alert-danger', 'Data Deleted Successfully!!');

                return redirect('composition-types');
            } catch (Exception $e) {
                DB::rollback();
                Session::flash('alert-danger', 'Something went wrong!');

                return redirect()->back();
            }
        } else {
            Session::flash('alert-danger', 'Can Not be Deleted ! It is currently associated with Others');

            return redirect('composition-types');
        }
    }
}
