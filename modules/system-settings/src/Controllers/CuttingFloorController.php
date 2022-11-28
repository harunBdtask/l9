<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Requests\CuttingFloorRequest;

class CuttingFloorController extends Controller
{
    public function index()
    {
        $cutting_floors = CuttingFloor::orderBy('id', 'DESC')->paginate();

        return view('system-settings::cuttingdroplets.cutting_floors', ['cutting_floors' => $cutting_floors]);
    }

    public function create()
    {
        return view('system-settings::cuttingdroplets.cutting_floor', ['cutting_floor' => null]);
    }

    public function store(CuttingFloorRequest $request)
    {
        $input = [
           'floor_no' => $request->floor_no,
           'factory_id' => currentUser()->factory_id,
        ];
        CuttingFloor::create($input);

        return redirect('/cutting-floors');
    }

    public function edit($id)
    {
        $cutting_floor = CuttingFloor::findOrFail($id);

        return view('system-settings::cuttingdroplets.cutting_floor', ['cutting_floor' => $cutting_floor]);
    }

    public function update($id, CuttingFloorRequest $request)
    {
        $floor = CuttingFloor::findOrFail($id);
        $floor->update(['floor_no' => $request->floor_no]);

        return redirect('/cutting-floors');
    }

    public function destroy($id)
    {
        $floor = CuttingFloor::findOrFail($id);
        $floor->delete();

        return redirect('/cutting-floors');
    }
}
