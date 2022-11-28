<?php

namespace SkylarkSoft\GoRMG\SecurityControl\Controllers;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

use SkylarkSoft\GoRMG\SecurityControl\Models\RegistrationDriver;
use SkylarkSoft\GoRMG\SecurityControl\Models\RegistrationVehicle;
use SkylarkSoft\GoRMG\SecurityControl\Models\VehicleAssign;

class VehicleSystemController extends Controller
{
    public function index()
    {
        $data['drivers'] = RegistrationDriver::OrderBy('id', 'desc')->paginate(5, ['*'], 'driver');
        $data['vehicles'] = RegistrationVehicle::OrderBy('id', 'desc')->paginate(5, ['*'], 'vehicle');
        $data['vehicle_edit'] = null;
        $data['driver_edit'] = null;

        return view('securitycontrol::pages.vehicle_manage_system', $data);
    }

    public function vehicleStore(Request $request, $id = null)
    {
        if ($id == null) {
            $request->validate([
                'vehicle_model' => 'required',
                'vehicle_registration' => 'required|unique:register_vehicles,vehicle_registration,NULL,NULL,deleted_at,NULL',
                'vehicle_engine' => 'required|unique:register_vehicles,vehicle_engine,NULL,NULL,deleted_at,NULL',
                'vehicle_chassis' => 'required|unique:register_vehicles,vehicle_chassis,NULL,NULL,deleted_at,NULL',
                'vehicle_name' => 'required|unique:register_vehicles,vehicle_name,NULL,NULL,deleted_at,NULL',
                'vehicle_type' => 'required',
            ]);
            $vehicle = new RegistrationVehicle();
        } else {
            $vehicle = RegistrationVehicle::find($id);
            $request->validate([
                'vehicle_model' => 'required',
                'vehicle_registration' => 'required|unique:register_vehicles,vehicle_registration,'.$vehicle->id.',id,deleted_at,NULL',
                'vehicle_engine' => 'required|unique:register_vehicles,vehicle_engine,'.$vehicle->id.',id,deleted_at,NULL',
                'vehicle_chassis' => 'required|unique:register_vehicles,vehicle_chassis,'.$vehicle->id.',id,deleted_at,NULL',
                'vehicle_name' => 'required|unique:register_vehicles,vehicle_name,'.$vehicle->id.',id,deleted_at,NULL',
                'vehicle_type' => 'required',
            ]);
        }
        $vehicle->vehicle_model = $request->vehicle_model;
        $vehicle->vehicle_registration = $request->vehicle_registration;
        $vehicle->vehicle_engine = $request->vehicle_engine;
        $vehicle->vehicle_chassis = $request->vehicle_chassis;
        $vehicle->vehicle_name = $request->vehicle_name;
        $vehicle->vehicle_type = $request->vehicle_type;
        $vehicle->save();
        if ($id == null) {
            Toastr::success('Vehicle Saved successfully');
        } else {
            Toastr::success('Vehicle Updated successfully');
        }

        return redirect()->route('vehicle.index');
    }

    public function vehicleEdit($id)
    {
        $data['drivers'] = RegistrationDriver::OrderBy('id', 'desc')->paginate(5, ['*'], 'driver');
        $data['vehicles'] = RegistrationVehicle::OrderBy('id', 'desc')->paginate(5, ['*'], 'vehicle');
        $data['vehicle_edit'] = RegistrationVehicle::find($id);

        return view('securitycontrol::pages.vehicle_manage_system', $data);
    }

    public function vehicleDelete($id)
    {
        $vehicle = RegistrationVehicle::find($id);
        $assigns = VehicleAssign::with('driver')->where('vehicle_id', $vehicle->id)->get();
        if (count($assigns) > 0) {
            foreach ($assigns as $assign) {
                $assign->driver()->update([
                    'status' => false,
                ]);
            }
            VehicleAssign::where('vehicle_id', $vehicle->id)->delete();
        }
        $vehicle->delete();
        Toastr::success('Vehicle Deleted Successfully');

        return redirect()->route('vehicle.index');
    }

    public function driverStore(Request $request, $id = null)
    {
        if ($id != null) {
            $driver = RegistrationDriver::find($id);
            $request->validate([
                'name' => 'required',
                'license_no' => 'required|unique:register_drivers,license_no,'.$driver->id.',id,deleted_at,NULL',
                'address' => 'required',
            ]);
        } else {
            $request->validate([
                'name' => 'required',
                'license_no' => 'required|unique:register_drivers,license_no,NULL,NULL,deleted_at,NULL',
                'address' => 'required',
            ]);
            $driver = new RegistrationDriver();
        }
        $driver->name = $request->name;
        $driver->license_no = $request->license_no;
        $driver->address = $request->address;
        $driver->save();
        if ($id == null) {
            Toastr::success('Driver Store Successfully');
        } else {
            Toastr::success('Driver Updated Successfully');
        }

        return redirect()->route('vehicle.index');
    }

    public function driverEdit($id)
    {
        $data['drivers'] = RegistrationDriver::OrderBy('id', 'desc')->paginate(5, ['*'], 'driver');
        $data['vehicles'] = RegistrationVehicle::OrderBy('id', 'desc')->paginate(5, ['*'], 'vehicle');
        $data['drive_edit'] = RegistrationDriver::find($id);

        return view('securitycontrol::pages.vehicle_manage_system', $data);
    }

    public function driverDelete($id)
    {
        $driver = RegistrationDriver::find($id);
        $assigns = VehicleAssign::with('driver')->where('driver_id', $driver->id)->get();
        if (count($assigns) > 0) {
            foreach ($assigns as $assign) {
                $assign->vehicle()->update([
                    'status' => false,
                ]);
            }
            VehicleAssign::where('driver_id', $driver->id)->delete();
        }
        $driver->delete();
        Toastr::success('Driver Deleted Successfully');

        return redirect()->route('vehicle.index');
    }
}
