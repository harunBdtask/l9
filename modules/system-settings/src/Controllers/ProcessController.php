<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Dompdf\Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Process;

class ProcessController extends Controller
{
    public function index()
    {
        $processes = Process::orderBy('id', 'desc')->paginate();

        return view('system-settings::process.list', compact('processes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'process_name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:process,process_name",
            'color_wise_charge_unit' => 'nullable|numeric',
        ]);

        try {
            Process::query()->create($request->all());
            Session::flash('success', 'Data stored successfully!!');
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
        }

        return redirect('/processes');
    }

    public function show($id)
    {
        return Process::findOrFail($id);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'process_name' => 'required|unique:process,process_name,' . $request->segment(2),
            'color_wise_charge_unit' => 'nullable|numeric',
        ]);

        try {
            $data = $request->all();
            if (! $request->has('color_wise_charge_unit')) {
                $data['color_wise_charge_unit'] = 0;
            }
            Process::findOrFail($id)->update($data);
            Session::flash('success', 'Data Updated successfully!!');
        } catch (Exception $e) {
            Session::flash('danger', 'Data stored Failed!!');
        }

        return redirect('/processes');
    }

    public function destroy($id)
    {
        Process::findOrFail($id)->delete();

        return redirect('/processes');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $processes = Process::where('process_name', 'like', '%' . $search . '%')->orderBy('id', 'desc')->paginate();

        return view('system-settings::process.list', compact('processes', 'search'));
    }
}
