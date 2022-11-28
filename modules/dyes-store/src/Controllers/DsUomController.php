<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\DyesStore\Models\DsUom;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\DyesStore\Requests\DsUomRequest;

class DsUomController extends Controller
{
    public function index(Request $request)
    {
        $uoms = DsUom::query()->filter($request->query('search'))->orderBy('created_at', 'DESC')->paginate();

        return view('dyes-store::pages.uoms', [
            "uoms" => $uoms
        ]);
    }

    public function create()
    {
        return view('dyes-store::forms.uom', ['uom' => null]);
    }

    public function store(DsUomRequest $request, DsUom $uom)
    {
        try {
            $uom->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('/dyes-store/uom');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function show($id)
    {
        //
    }

    public function edit(DsUom $uom)
    {
        return view('dyes-store::forms.uom', ['uom' => $uom]);
    }

    public function update(DsUomRequest $request, DsUom $uom)
    {
        try {
            $uom->fill($request->all())->save();
            Session::flash('alert-success', 'Data Updated Successfully!!');

            return redirect('/dyes-store/uom');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function destroy(DsUom $uom)
    {
        try {
            $uom->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');

            return redirect('/dyes-store/uom');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }
}
