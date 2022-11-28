<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;
use SkylarkSoft\GoRMG\DyesStore\Models\DsRack;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\DyesStore\Requests\DsRackRequest;

class DsRackController extends Controller
{
    public function index(Request $request)
    {
        $racks = DsRack::query()->filter($request->query('search'))->orderBy('created_at', 'DESC')->paginate();

        return view('dyes-store::pages.racks', [
            "racks" => $racks
        ]);
    }

    public function create()
    {
        return view('dyes-store::forms.rack', ['rack' => null]);
    }

    public function store(DsRackRequest $request, DsRack $rack)
    {
        try {
            $rack->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('/dyes-store/racks');
        } catch (\Exception $e) {
            Session::flash('alert-danger', "Something went wrong!{$e->getMessage()}");

            return redirect()->back();
        }
    }

    public function show($id)
    {
        //
    }

    public function edit(DsRack $rack)
    {
        return view('dyes-store::forms.rack', ['rack' => $rack]);
    }

    public function update(DsRackRequest $request, DsRack $rack)
    {
        try {
            $rack->fill($request->all())->save();
            Session::flash('alert-success', 'Data Updated Successfully!!');

            return redirect('/dyes-store/racks');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function destroy(DsRack $rack)
    {
        try {
            $rack->delete();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('/dyes-store/racks');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }
}
