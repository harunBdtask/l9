<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Section;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueSection;

class SectionCreationController extends Controller
{
    public function index()
    {
        $sections = Section::orderBy('id', 'DESC')->paginate();

        return view('system-settings::section.section_list', ['sections' => $sections]);
    }

    public function itemsStore(Request $request)
    {
        $request->validate([
            'name' => ['required',"not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i", new UniqueSection()],
        ]);

        if (Section::create($request->all())) {
            Session::flash('alert-success', 'Data Stored Successfully!!');
        } else {
            Session::flash('alert-danger', 'Data Update Successfully!!');
        }

        return redirect('section');
    }

    public function edit($id)
    {
        return Section::findOrFail($id);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $is_update = Section::findorFail($request->id)->update($request->all());
        if ($is_update) {
            Session::flash('alert-success', 'Data Updated Successfully!!');
        } else {
            Session::flash('alert-danger', 'Data Updated Failed!!');
        }

        return redirect('/section');
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $shifts = Section::findOrFail($id);
            $shifts->delete();
            DB::commit();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('section');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!Error Code Prty.D-102');

            return redirect()->back();
        }
    }

    public function search(Request $request)
    {
        $q = $request->get('q');
        $sections = Section::where('name', 'like', '%'.$q.'%')->orWhere('description', 'like', '%'.$q.'%')->orderBy('id', 'DESC')->paginate();

        return view('system-settings::section.section_list', compact('sections'));
    }
}
