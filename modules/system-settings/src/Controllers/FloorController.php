<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Requests\FloorRequest;
use SkylarkSoft\GoRMG\SystemSettings\Services\FloorServices;
use SkylarkSoft\GoRMG\SystemSettings\Services\LineServices;

class FloorController extends Controller
{
    public function index()
    {
        $query = Floor::query();
        $query->when(request('q') != null, function ($q) {
            return $q->where('floor_no', request('q'));
        });
        $floors = $query->orderBy('id', 'desc')->paginate();

        return view('system-settings::inputdroplets.floors', [
            'floors' => $floors,
        ]);
    }

    public function create()
    {
        return view('system-settings::inputdroplets.floor', ['floor' => null]);
    }

    public function store(FloorRequest $request)
    {
        try {
            Floor::create($request->all());
            Session::flash('success', S_SAVE_MSG);
        } catch (\Exception $e) {
            Session::flash('sucess', $e->getMassage());
        }

        return redirect('/floors');
    }

    public function edit($id)
    {
        $floor = Floor::findOrFail($id);

        return view('system-settings::inputdroplets.floor', ['floor' => $floor]);
    }

    public function update($id, FloorRequest $request)
    {
        try {
            $floor = Floor::findOrFail($id);
            $floor->update(['floor_no' => $request->floor_no]);
            Session::flash('success', S_UPDATE_MSG);
        } catch (\Exception $e) {
            Session::flash('sucess', $e->getMassage());
        }

        return redirect('/floors');
    }

    public function destroy($id)
    {
        try {
            $floor = Floor::with('lines')->findOrFail($id);
            if ($floor->lines->count() == 0) {
                $floor->delete();
                Session::flash('success', S_DEL_MSG);
            } else {
                Session::flash('error', 'Please line and others related data delete first');
            }
        } catch (\Exception $e) {
            Session::flash('error', $e->getMassage());
        }

        return redirect('/floors');
    }


    public function getFloors(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'type' => 'All Floors',
            'data' => FloorServices::getAllFloors(),
        ]);
    }

    public function getLines($id): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'type' => 'All Lines',
            'data' => LineServices::getAllLines($id),
        ]);
    }

    public function getLinesForDropdown($id): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'type' => 'All Lines',
            'data' => LineServices::getAllLinesForDropdown($id),
        ]);
    }
}
