<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\SampleFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\SampleLine;

class SampleLineController extends Controller
{
    public function index()
    {
        $floors = SampleFloor::pluck('floor_no', 'id')->all();
        $lines = SampleLine::with('floor:id,floor_no')->orderBy('sort', 'asc')->orderBy('id', 'desc')->paginate();
        $request = request()->all() ?? null;
        $q = $request['q']??null;
        if (!empty($q)) {
            $lines = SampleLine::withoutGlobalScope('factoryId')
            ->with('floor:id,floor_no')
            ->join('floors', 'floors.id', '=', 'lines.floor_id')
            ->where('lines.factory_id', factoryId())
            ->where(function ($query) use ($q) {
                return $query->where('lines.line_no', 'like', '%' . $q . '%')
                    ->orWhere('floors.floor_no', 'like', '%' . $q . '%');
            })
            ->select('lines.*', 'floors.floor_no')
            ->orderBy('lines.sort', 'asc')
            ->orderBy('lines.id', 'desc')
            ->paginate();
        }

        return view('system-settings::sample-line.lines', [
            'lines' => $lines,
            'floors' => $floors,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'floor_id' => 'required',
            'line_no' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:sample_lines,line_no,",
            'sort' => 'nullable|integer|min:0|not_in:0',
        ]);
        try {
            SampleLine::create($request->all());
            Session::flash('success', S_SAVE_MSG);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        }
        return redirect()->back();
    }

    public function edit(SampleLine $sampleLine)
    {
        return response()->json($sampleLine);
    }

    public function update(Request $request, SampleLine $sampleLine)
    {
        $request->validate([
            'floor_id' => 'required',
            'line_no' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:sample_lines,line_no,". $sampleLine->id,
            'sort' => 'nullable|integer|min:0|not_in:0',
        ]);
        $sampleLine->fill($request->all())->save();
        Session::flash('success', 'Successfully Updated');
        return redirect()->back();
    }

    public function destroy(SampleLine $sampleLine)
    {
        $sampleLine->delete();
        Session::flash('success', S_DEL_MSG);
        return redirect()->back();
    }

    public function sampleLines()
    {
        try {
            $data = SampleLine::select('id', 'line_no', 'floor_id')->get();
            return response()->json($data);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

}
