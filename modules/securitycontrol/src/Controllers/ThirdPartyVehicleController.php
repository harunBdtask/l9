<?php

namespace SkylarkSoft\GoRMG\SecurityControl\Controllers;

use App\Http\Controllers\Controller;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SkylarkSoft\GoRMG\SecurityControl\Models\ThirdPartyVehicle;

class ThirdPartyVehicleController extends Controller
{
    public function index()
    {
        $data['third_vehicle'] = null;
        $data['third_vehicles'] = ThirdPartyVehicle::orderBy('id', 'desc')->paginate(5);

        return view('securitycontrol::pages.third_party_vehicle_system', $data);
    }

    public function edit($id)
    {
        $data['third_vehicles'] = ThirdPartyVehicle::orderBy('id', 'desc')->paginate(5);
        $data['third_vehicle'] = ThirdPartyVehicle::find($id);

        return view('securitycontrol::pages.third_party_vehicle_system', $data);
    }

    //['nullable','email',Rule::unique('visitors_tracking')->where(function ($query){
//    return $query->where('status',true);
    //})],
    public function store(Request $request, $id = null)
    {
        if ($id == null) {
            $request->validate([
                'vehicle_name' => 'required',
                'vehicle_registration' => ['required',Rule::unique('third_party_vehicles')->where(function ($query) {
                    return $query->where('status', true);
                })],
                'driver_name' => 'required',
                'driver_license' => ['required',Rule::unique('third_party_vehicles')->where(function ($query) {
                    return $query->where('status', true);
                })],
                'purpose' => 'required',
            ]);
            $thirdParty = new ThirdPartyVehicle();
        } else {
            $thirdParty = ThirdPartyVehicle::find($id);
            $request->validate([
                'vehicle_name' => 'required',
                'vehicle_registration' => ['required',Rule::unique('third_party_vehicles')->where(function ($query) {
                    return $query->where('status', true);
                })->ignore($thirdParty->id)],
                'driver_name' => 'required',
                'driver_license' => ['required',Rule::unique('third_party_vehicles')->where(function ($query) {
                    return $query->where('status', true);
                })->ignore($thirdParty->id)],
                'purpose' => 'required',
            ]);
        }
        $thirdParty->status = true;
        $thirdParty->vehicle_name = $request->vehicle_name;
        $thirdParty->vehicle_registration = $request->vehicle_registration;
        $thirdParty->driver_name = $request->driver_name;
        $thirdParty->driver_license = $request->driver_license;
        $thirdParty->purpose = $request->purpose;
        $thirdParty->save();
        if ($id == null) {
            Toastr::success('Vehicle saved successfully');
        } else {
            Toastr::success('Vehicle updated successfully');
        }

        return redirect()->route('third.vehicle.index');
    }

    public function delete($id)
    {
        ThirdPartyVehicle::find($id)->delete();
        Toastr::success('Vehicle deleted successfully');

        return redirect()->route('third.vehicle.index');
    }

    public function statusUpdate($id)
    {
        $thirdParty = ThirdPartyVehicle::find($id);
        $thirdParty->update([
           'status' => false,
        ]);
        Toastr::success('Vehicle check out from the factory');

        return redirect()->route('third.vehicle.index');
    }
}
