<?php

namespace SkylarkSoft\GoRMG\McInventory\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use SkylarkSoft\GoRMG\McInventory\Models\MachineLocation;
use SkylarkSoft\GoRMG\McInventory\Requests\MachineLocationFormRequest;
use SkylarkSoft\GoRMG\McInventory\Constants\McMachineInventoryConstant;

class MachineLocationController extends Controller
{
    protected function machineLocations(Request $request): LengthAwarePaginator
    {
        return MachineLocation::query()->orderBy('id','desc')
            ->when($request->get('location_type_filter'), function ($query) use ($request) {
                $query->where('location_type', "{$request->get('location_type_filter')}");
            })
            ->when($request->get('location_name_filter'), function ($query) use ($request) {
                $query->where('location_name', 'LIKE', "%{$request->get('location_name_filter')}%");
            })
            ->when($request->get('address_name_filter'), function ($query) use ($request) {
                $query->where('address', 'LIKE', "%{$request->get('address_name_filter')}%");
            })
            ->when($request->get('contact_no_filter'), function ($query) use ($request) {
                $query->where('contact_no', 'LIKE', "%{$request->get('contact_no_filter')}%");
            })
            ->when($request->get('email_filter'), function ($query) use ($request) {
                $query->where('email', 'LIKE', "%{$request->get('email_filter')}%");
            })
            ->when($request->get('attention_filter'), function ($query) use ($request) {
                $query->where('attention', 'LIKE', "%{$request->get('attention_filter')}%");
            })
            ->orderByDesc('id')
            ->paginate();
    }

    public function index(Request $request)
    {
        $machineLocations = $this->machineLocations($request);
        $locationTypes = collect(McMachineInventoryConstant::LOCATION_TYPES)->prepend('Select','');
        return view('McInventory::libraries.machine-location',[
            'machineLocations' => $machineLocations,
            'machineLocation' => null,
            'locationTypes' => $locationTypes
        ]);
    }

    public function  store(MachineLocationFormRequest $request,MachineLocation $machineLocation): RedirectResponse
    {
        try {
            $machineLocation->fill($request->all())->save();
            Session::flash('alert-success','Data Stored Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('machine-location.index');
        }
    }

    public function edit(Request $request, MachineLocation $machineLocation)
    {
        try {
            $machineLocations = $this->machineLocations($request);
            $locationTypes = collect(McMachineInventoryConstant::LOCATION_TYPES)->prepend('Select','0');
            return view('McInventory::libraries.machine-location',[
                'machineLocations' => $machineLocations,
                'machineLocation' => $machineLocation,
                'locationTypes' => $locationTypes
            ]);
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
            return back();
        }
    }

    public function update(MachineLocationFormRequest $request, MachineLocation $machineLocation): RedirectResponse
    {
        try {
            $machineLocation->fill($request->all())->save();
            Session::flash('alert-success','Data Updated Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('machine-location.index');
        }
    }

    public function destroy(MachineLocation $machineLocation): RedirectResponse
    {
        try {
            $machineLocation->delete();
            Session::flash('alert-danger','Data Deleted Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
