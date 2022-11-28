<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Requests\UnitOfMeasurementsRequest;

class UnitOfMeasurementsController extends Controller
{
    public function index()
    {
        $unitOfMeasurements = UnitOfMeasurement::orderBy('unit_of_measurement', 'ASC')->paginate();

        return view('system-settings::pages.unit_of_measurements', compact('unitOfMeasurements'));
    }

    public function show($id)
    {
        return UnitOfMeasurement::findOrFail($id);
    }

    public function store(UnitOfMeasurementsRequest $request)
    {
        try {
            UnitOfMeasurement::create($request->all());
            Session::flash('success', 'Data Stored Successfully!!');
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong');
        }

        return redirect('unit-of-measurements');
    }

    public function update($id, UnitOfMeasurementsRequest $request)
    {
        try {
            UnitOfMeasurement::findOrFail($id)->update($request->all());
            Session::flash('success', 'Data Updated Successfully!!');
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong');
        }

        return redirect('unit-of-measurements');
    }

    public function destroy($id)
    {
        UnitOfMeasurement::findOrFail($id)->delete();
        Session::flash('error', 'Data Deleted Successfully');

        return redirect('unit-of-measurements');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $unitOfMeasurements = UnitOfMeasurement::where('unit_of_measurement', 'like', '%' . $search . '%')->orderBy('unit_of_measurement', 'ASC')->paginate();

        return view('system-settings::pages.unit_of_measurements', compact('unitOfMeasurements', 'search'));
    }
}
