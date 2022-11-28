<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\DyesStore\Models\DsStorageLocation;
use Illuminate\Support\Facades\Session;

class DsStorageLocationController extends Controller
{
    public function index(Request $request)
    {
        $storageLocations = DsStorageLocation::query()->filter($request->query('search'))->orderBy('created_at', 'DESC')->paginate();

        return view('dyes-store::pages.storage_location', [
            "storageLocations" => $storageLocations
        ]);
    }

    public function create()
    {
        return view('dyes-store::forms.storage_location', ['storageLocations' => null]);
    }

    public function store(Request $request)
    {
        try {
            $storageLocation = new DsStorageLocation();
            $storageLocation->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('/dyes-store/storage-location');
        } catch (\Exception $e) {
            Session::flash('alert-danger', "Something went wrong!{$e->getMessage()}");

            return redirect()->back();
        }
    }

    public function show(DsStorageLocation $storageLocation)
    {
        return view('dyes-store::forms.storage_location', ['storageLocations' => $storageLocation]);
    }

    public function edit(DsStorageLocation $storageLocation)
    {
        return view('dyes-store::forms.storage_location', ['storageLocations' => $storageLocation]);

    }

    public function update(Request $request, DsStorageLocation $storageLocation)
    {
        try {
            $storageLocation->fill($request->all())->save();
            Session::flash('alert-success', 'Data Updated Successfully!!');

            return redirect('/dyes-store/storage-location');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function destroy(DsStorageLocation $storageLocation)
    {
        try {
            $storageLocation->delete();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('/dyes-store/storage-location');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }
}
