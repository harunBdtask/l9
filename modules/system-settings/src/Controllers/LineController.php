<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use SkylarkSoft\GoRMG\SystemSettings\Requests\LineRequest;

class LineController extends Controller
{
    public function index()
    {
        $lines = Line::with('floor:id,floor_no')->orderBy('sort', 'asc')->orderBy('id', 'desc')->paginate();

        return view('system-settings::inputdroplets.lines', [
            'lines' => $lines,
        ]);
    }

    public function create()
    {
        $floors = Floor::pluck('floor_no', 'id')->all();

        return view('system-settings::inputdroplets.line', [
            'line' => null,
            'floors' => $floors,
        ]);
    }

    public function store(LineRequest $request)
    {
        try {
            Line::create($request->all());
            Session::flash('success', S_SAVE_MSG);
        } catch (Exception $e) {
            Session::flash('error', $e->getMassage());
        }

        return redirect('/lines');
    }

    public function edit($id)
    {
        $floors = Floor::pluck('floor_no', 'id')->all();
        $line = Line::findOrFail($id);

        return view('system-settings::inputdroplets.line', [
            'floors' => $floors,
            'line' => $line,
        ]);
    }

    public function update($id, LineRequest $request)
    {
        try {
            DB::beginTransaction();
            $line = Line::find($id);
            $floorId = $line->floor_id;
            $line->floor_id = $request->floor_id;
            $line->line_no = $request->line_no;
            $line->sort = $request->sort;
            $line->save();

            //if ($line->isDirty('floor_id')) not working confused
            if ($floorId != $request->floor_id) {
                // use query builder for updated_at remaing same
                DB::table('hourly_sewing_production_reports')->where('line_id', $line->id)
                    ->update([
                        'floor_id' => $request->floor_id,
                    ]);
                DB::table('date_wise_sewing_production_reports')->where('line_id', $line->id)
                    ->update([
                        'floor_id' => $request->floor_id,
                    ]);
                DB::table('finishing_production_reports')->where('line_id', $line->id)
                    ->update([
                        'floor_id' => $request->floor_id,
                    ]);
                DB::table('sewing_line_targets')->where('line_id', $line->id)
                    ->update([
                        'floor_id' => $request->floor_id,
                    ]);
            }
            DB::commit();
            Session::flash('success', S_UPDATE_MSG);
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', $e->getMessage());
        }

        return redirect('/lines');
    }

    public function destroy($id)
    {
        try {
            $hourly = DB::table('sewingoutputs')
                ->whereNull('deleted_at')
                ->where('line_id', $id)
                ->count();
            $target = DB::table('sewing_line_targets')
                ->whereNull('deleted_at')
                ->where('line_id', $id)
                ->count();
            if ($hourly != 0 || $target != 0) {
                Session::flash('error', 'You can\'t delete because input or output or others associated data with it');

                return redirect()->back();
            }

            $line = Line::findOrFail($id);
            $line->delete();
            Session::flash('success', S_DEL_MSG);
        } catch (Exception $e) {
            Session::flash('success', $e->getMessage());
        }

        return redirect('/lines');
    }

    public function getFloors($factory_id)
    {
        return Floor::where('factory_id', $factory_id)->pluck('floor_no', 'id')->toArray();
    }

    public function getLines($floor_id)
    {
        return Line::where('floor_id', $floor_id)->pluck('line_no', 'id')->toArray();
    }

    public function searchLines(Request $request)
    {
        $lines = Line::withoutGlobalScope('factoryId')
            ->with('floor:id,floor_no')
            ->join('floors', 'floors.id', '=', 'lines.floor_id')
            ->where('lines.factory_id', factoryId())
            ->where(function ($q) use ($request) {
                return $q->where('lines.line_no', 'like', '%' . $request->q . '%')
                    ->orWhere('floors.floor_no', 'like', '%' . $request->q . '%');
            })
            ->select('lines.*', 'floors.floor_no')
            ->orderBy('lines.sort', 'asc')
            ->orderBy('lines.id', 'desc')
            ->paginate();

        return view('system-settings::inputdroplets.lines', [
            'lines' => $lines,
        ]);
    }
}
