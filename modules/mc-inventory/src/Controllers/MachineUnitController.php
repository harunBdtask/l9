<?php

namespace SkylarkSoft\GoRMG\McInventory\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\McInventory\Models\MachineUnit;
use SkylarkSoft\GoRMG\McInventory\Requests\MachineUnitFormRequest;

class MachineUnitController extends Controller
{
    protected function machineUnits(Request $request): LengthAwarePaginator
    {
        return MachineUnit::query()->orderBy('id','desc')
            ->when($request->get('machine_name_filter'), function ($query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->get('machine_name_filter')}%");
            })
            ->when($request->get('type'), function ($query) use ($request) {
                $query->where('type',$request->get('type'));
            })
            ->when($request->get('machine_description_filter'), function ($query) use ($request) {
                $query->where('description', 'LIKE', "%{$request->get('machine_description_filter')}%");
            })
            ->orderByDesc('id')
            ->paginate();
    }

    public function index(Request $request)
    {
        $machineUnits = $this->machineUnits($request);
        return view('McInventory::libraries.machine-unit',[
            'machineUnits' => $machineUnits,
            'machineUnit' => null,
        ]);
    }

    public function  store(MachineUnitFormRequest $request,MachineUnit $machineUnit): RedirectResponse
    {
//        dd($request->all());
        try {
            $machineUnit->fill($request->all())->save();
            Session::flash('alert-success', 'Data Created Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('machine-unit.index');
        }
    }

    public function edit(Request $request, MachineUnit $machineUnit)
    {
        try {
            $machineUnits = $this->machineUnits($request);
            return view('McInventory::libraries.machine-unit',[
                'machineUnits' => $machineUnits,
                'machineUnit' => $machineUnit,
            ]);
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
            return back();
        }
    }

    public function update(MachineUnitFormRequest $request, MachineUnit $machineUnit): RedirectResponse
    {
        try {
            $machineUnit->fill($request->all())->save();
            Session::flash('alert-success', 'Data Updated Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('machine-unit.index');
        }
    }

    public function destroy(MachineUnit $machineUnit): RedirectResponse
    {
        try {
            $machineUnit->delete();
            Session::flash('alert-danger', 'Data Deleted Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
