<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Part;
use SkylarkSoft\GoRMG\SystemSettings\Requests\PartRequest;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.parts', ['parts' => $parts]);
    }

    public function create()
    {
        return view('system-settings::forms.part', ['part' => null]);
    }

    public function store(PartRequest $request)
    {
        Part::create($request->all());
        Session::flash('alert-success', 'Data stored successfully!!');

        return redirect('/parts');
    }

    public function edit($id)
    {
        $part = Part::findOrFail($id);

        return view('system-settings::forms.part', ['part' => $part]);
    }

    public function update($id, PartRequest $request)
    {
        $part = Part::findOrFail($id);
        $part->update($request->all());
        Session::flash('alert-success', 'Data updated successfully!!');

        return redirect('/parts');
    }

    public function destroy($id)
    {
        $part = Part::findOrFail($id);
        $part->delete();
        Session::flash('alert-danger', 'Data deleted successfully!!');

        return redirect('/parts');
    }

    public function searchParts(Request $request)
    {
        $parts = Part::where('name', 'like', '%' . $request->q . '%')
            ->orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.parts', ['parts' => $parts, 'q' => $request->q]);
    }
}
