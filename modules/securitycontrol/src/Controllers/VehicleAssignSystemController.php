<?php

namespace SkylarkSoft\GoRMG\SecurityControl\Controllers;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SecurityControl\Models\RegistrationDriver;
use SkylarkSoft\GoRMG\SecurityControl\Models\RegistrationVehicle;
use SkylarkSoft\GoRMG\SecurityControl\Models\VehicleAssign;

class VehicleAssignSystemController extends Controller
{
    public function index()
    {
        $data['assigns'] = VehicleAssign::with(['driver','vehicle'])->orderBy('id', 'desc')->paginate(5);
        $data['vehicles'] = RegistrationVehicle::where('status', false)->pluck('vehicle_name', 'id');
        $data['drivers'] = RegistrationDriver::where('status', false)->pluck('name', 'id');

        return view('securitycontrol::pages.vehicle_assign_system', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'driver' => 'required',
            'vehicle' => 'required',
            'destination' => 'required',
        ]);
        $assign = new VehicleAssign();

        $assign->driver_id = $request->driver;
        $assign->vehicle_id = $request->vehicle;
        $assign->driver()->update([
            'status' => true,

        ]);
        $assign->vehicle()->update([
            'status' => true,

        ]);

        if ($request->from == null) {
            $assign->from = factoryId();
        } else {
            $assign->from = $request->from;
        }
        $assign->to = $request->destination;
        $assign->assign_person = userId();
        $assign->out_time = now();
        $assign->save();
        Toastr::success('Vehicle Assigned to the driver');

        return redirect()->route('vehicle-assign-index');
    }

    public function statusUpdate($id)
    {
        $assign = VehicleAssign::find($id);
        $assign->in_time = now();
        $travelTime = strtotime($assign->in_time) - strtotime($assign->out_time);
        $assign->travel_time = $travelTime;
        $assign->driver()->update([
            'status' => false,

        ]);
        $assign->vehicle()->update([
            'status' => false,

        ]);
        $assign->save();
        Toastr::success('Vehicle received successfully');

        return redirect()->route('vehicle-assign-index');
    }

    public function delete($id)
    {
        $assign = VehicleAssign::find($id);
        $assign->driver()->update([
            'status' => false,

        ]);
        $assign->vehicle()->update([
            'status' => false,

        ]);
        $assign->delete();
        Toastr::success('Assigned vehicle deleted successfully');

        return redirect()->route('vehicle-assign-index');
    }
}
