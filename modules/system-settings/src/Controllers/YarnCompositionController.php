<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricCompositionDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Requests\YarnCompositionRequest;

class YarnCompositionController extends Controller
{
    public function index()
    {
        if (getRole() == 'super-admin') {
            $yarnCompositions = YarnComposition::orderBy('id', 'DESC')->paginate();
        } else {
            $yarnCompositions = YarnComposition::where('factory_id', Auth::user()->factory_id)->orderBy('id', 'DESC')->paginate();
        }

        return view('system-settings::pages.yarn_compositions', compact('yarnCompositions'));
    }

    public function store(YarnCompositionRequest $request)
    {
        try {
            $data = YarnComposition::create($request->all());

            if (Request::capture()->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $data
                ]);
            }

            Session::flash('success', 'Data Stored Successfully!!');
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong');
        }

        return redirect('/yarn-compositions');
    }

    public function show($id)
    {
        return YarnComposition::findOrFail($id);
    }

    public function update($id, YarnCompositionRequest $request)
    {
        try {
            YarnComposition::findOrFail($id)->update($request->all());
            Session::flash('success', 'Data Updated Successfully!!');
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong');
        }

        return redirect('/yarn-compositions');
    }

    public function destroy($id)
    {
        $fabricCompositionDetails = NewFabricCompositionDetail::where('yarn_composition_id', $id)->first();

        if (! isset($fabricCompositionDetails)) {
            YarnComposition::findOrFail($id)->delete();
            Session::flash('error', 'Data Deleted Successfully');
        } else {
            Session::flash('error', 'Can Not be Deleted ! It is currently associated with Others');
        }

        return redirect('/yarn-compositions');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $yarnCompositions = YarnComposition::where('yarn_composition', 'like', '%' . $search . '%')->paginate();

        return view('system-settings::pages.yarn_compositions', compact('yarnCompositions', 'search'));
    }
}
