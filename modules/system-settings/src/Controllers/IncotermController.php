<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Incoterm;
use SkylarkSoft\GoRMG\SystemSettings\Requests\IncotermRequest;

class IncotermController extends Controller
{
    public function index()
    {
        $incoterms = Incoterm::with('preparedBy')->orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.incoterms', compact('incoterms'));
    }

    public function store(IncotermRequest $request)
    {
        try {
            Incoterm::create($request->all());
            Session::flash('success', 'Data Created successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('/incoterms');
    }

    public function show($id)
    {
        return Incoterm::findOrFail($id);
    }

    public function update($id, IncotermRequest $request)
    {
        try {
            Incoterm::findOrFail($id)->update($request->all());
            Session::flash('success', 'Data Updated successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('/incoterms');
    }

    public function destroy($id)
    {
        Incoterm::findOrFail($id)->delete();
        Session::flash('error', 'Data Deleted Successfully');

        return redirect('/incoterms');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $incoterms = Incoterm::with('preparedBy')->where('incoterm', 'like', '%' . $search . '%')->orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.incoterms', compact('incoterms'));
    }
}
