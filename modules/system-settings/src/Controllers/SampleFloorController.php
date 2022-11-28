<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\SampleFloor;

class SampleFloorController extends Controller
{
    public function index()
    {
        $datas = SampleFloor::latest()->paginate();
        $request = request()->all() ?? null;
        $q = $request['q']??null;
        if (!empty($q)) {
            $datas = SampleFloor::query()
            ->where('floor_no', 'like', '%' . $q . '%')
            ->letest()
            ->paginate();
        }

        return view('system-settings::sample-line.floors', [
            'datas' => $datas,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'floor_no' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:sample_floors,floor_no,",
        ]);
        try {
            SampleFloor::create($request->all());
            Session::flash('success', S_SAVE_MSG);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        }
        return redirect()->back();
    }

    public function edit(SampleFloor $sampleFloor)
    {
        return response()->json($sampleFloor);
    }

    public function update(Request $request, SampleFloor $sampleFloor)
    {
        $request->validate([
            'floor_no' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:sample_floors,floor_no,". $sampleFloor->id,
        ]);
        $sampleFloor->fill($request->all())->save();
        Session::flash('success', 'Successfully Updated');
        return redirect()->back();
    }

    public function destroy(SampleFloor $sampleFloor)
    {
        $sampleFloor->delete();
        Session::flash('success', S_DEL_MSG);
        return redirect()->back();
    }

}
