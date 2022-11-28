<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\SystemSettings\Models\Fabric_composition;
use SkylarkSoft\GoRMG\SystemSettings\Requests\FabricCompositionRequest;

class FabricComposition extends Controller
{
    public function index()
    {
        $data['fabric_compositions'] = Fabric_composition::with('factory')->orderBy('created_at', 'desc')->paginate();

        return view('system-settings::fabric_compositions.list', $data);
    }

    public function create()
    {
        $data['fabric_composition'] = null;

        return view('system-settings::fabric_compositions.create_update', $data);
    }

    public function store(FabricCompositionRequest $request)
    {
        try {
            $id = $request->id ?? null;
            $composition = Fabric_composition::findOrNew($id);
            $composition->yarn_composition = strtoupper($request->yarn_composition);
            $composition->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('fabric-compositions');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Data Stored Failed!!');

            return redirect()->back()->withInput();
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $data['fabric_composition'] = Fabric_composition::find($id);

        return view('system-settings::fabric_compositions.create_update', $data);
    }

    public function searchFabricComposition(Request $request)
    {
        $query = $request->q;
        $data['fabric_compositions'] = Fabric_composition::where('yarn_composition', 'like', '%' . $query . '%')
            ->orderBy('id', 'DESC')->paginate();

        return view('system-settings::fabric_compositions.list', $data);
    }

    public function pdfDownload(Request $request)
    {
        $data['fabric_compositions'] = Fabric_composition::with('factory')->orderBy('factory_id', 'asc')->get();
        $pdf = PDF::loadView('system-settings::fabric_compositions.pdf', $data);

        return $pdf->download('fabrication_list.pdf');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $composition = Fabric_composition::find($id)->delete();
        if ($composition) {
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect()->back();
        }
        Session::flash('alert-danger', 'Data Delete Failed!!');

        return redirect()->back();
    }
}
