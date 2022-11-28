<?php

namespace SkylarkSoft\GoRMG\McInventory\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\McInventory\Models\MachineType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use SkylarkSoft\GoRMG\McInventory\Requests\MachineTypeFormRequest;
use SkylarkSoft\GoRMG\McInventory\Constants\McMachineInventoryConstant;

class MachineTypeController extends Controller
{
    protected function machineTypes(Request $request): LengthAwarePaginator
    {
        return MachineType::query()->orderBy('id','desc')
            ->when($request->get('machine_category_filter'), function ($query) use ($request) {
                $query->where('machine_category', "{$request->get('machine_category_filter')}");
            })
            ->when($request->get('machine_type_filter'), function ($query) use ($request) {
                $query->where('machine_type', 'LIKE', "%{$request->get('machine_type_filter')}%");
            })
            ->when($request->get('machine_description_filter'), function ($query) use ($request) {
                $query->where('description', 'LIKE', "%{$request->get('machine_description_filter')}%");
            })
            ->orderByDesc('id')
            ->paginate();
    }

    public function index(Request $request)
    {
        $machineTypes = $this->machineTypes($request);
        $machineCategories = collect(McMachineInventoryConstant::MACHINE_CATEGORIES)->prepend('Select','');
        return view('McInventory::libraries.machine-type',[
            'machineTypes' => $machineTypes,
            'machineType' => null,
            'machineCategories' => $machineCategories
        ]);
    }

    public function  store(MachineTypeFormRequest $request,MachineType $machineType): RedirectResponse
    {
        try {
            $machineType->fill($request->all())->save();
            Session::flash('alert-success', 'Machine Type Created Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('machine-type.index');
        }
    }

    public function edit(Request $request, MachineType $machineType)
    {
        try {
            $machineTypes = $this->machineTypes($request);
            $machineCategories = collect(McMachineInventoryConstant::MACHINE_CATEGORIES)->prepend('Select','0');
            return view('McInventory::libraries.machine-type',[
                'machineTypes' => $machineTypes,
                'machineType' => $machineType,
                'machineCategories' => $machineCategories
            ]);
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
            return back();
        }
    }

    public function update(MachineTypeFormRequest $request, MachineType $machineType): RedirectResponse
    {
        try {
            $machineType->fill($request->all())->save();
            Session::flash('alert-success', 'Machine Type Updated Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('machine-type.index');
        }
    }

    public function destroy(MachineType $machineType): RedirectResponse
    {
        try {
            $machineType->delete();
            Session::flash('alert-success', 'Machine Type Deleted Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
