<?php

namespace SkylarkSoft\GoRMG\McInventory\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\McInventory\Models\MachineType;
use SkylarkSoft\GoRMG\McInventory\Models\MachineSubType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use SkylarkSoft\GoRMG\McInventory\Requests\MachineSubTypeFormRequest;
use SkylarkSoft\GoRMG\McInventory\Constants\McMachineInventoryConstant;

class MachineSubTypeController extends Controller
{
    protected function machineSubTypes(Request $request): LengthAwarePaginator
    {
        return MachineSubType::query()->orderBy('id','desc')
            ->with('machineType')
            ->when($request->get('category'), function ($query) use ($request) {
                $query->where('machine_category', $request->get('category'));
            })
            ->when($request->get('machine_type'), function ($query) use ($request) {
                $query->where('machine_type', 'LIKE', "%".$request->get('machine_type')."%");
            })
            ->when($request->get('subtype'), function ($query) use ($request) {
                $query->where('machine_sub_type', 'LIKE', "%".$request->get('subtype')."%");
            })
            ->when($request->get('description'), function ($query) use ($request) {
                $query->where('description', 'LIKE', "%".$request->get('description')."%");
            })
            ->orderByDesc('id')
            ->paginate();
    }

    public function index(Request $request)
    {
        $machineSubTypes = $this->machineSubTypes($request);
        $machineTypes = MachineType::pluck('machine_type as text', 'id')->prepend('Select','');
        $machineCategories = collect(McMachineInventoryConstant::MACHINE_CATEGORIES)->prepend('Select','');
        return view('McInventory::libraries.machine-sub-type',[
            'machineSubTypes' => $machineSubTypes,
            'machineSubType' => null,
            'machineCategories' => $machineCategories,
            'machineType' => null,
            'machineTypes' => $machineTypes
        ]);
    }

    public function getMachineType(Request $request)
    {
        $machineCategory = $request->machine_category;

        $machineType = MachineType::query()->where('machine_category',$machineCategory)->get();
        return response()->json($machineType);
    }

    public function  store(MachineSubTypeFormRequest $request,MachineSubType $machineSubType): RedirectResponse
    {
        try {
            $machineSubType->fill($request->all())->save();
            Session::flash('alert-success','Data Stored Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('machine-sub-type.index');
        }
    }

    public function edit(Request $request, MachineSubType $machineSubType)
    {
        try {
            $machineSubTypes = $this->machineSubTypes($request);
            $machineCategories = collect(McMachineInventoryConstant::MACHINE_CATEGORIES)->prepend('Select','0');
            $machineType = MachineType::query()->where('machine_category',$machineSubType->machine_category)->get();
            
            $machineTypes = MachineType::pluck('machine_type as text', 'id')->prepend('Select','');

            return view('McInventory::libraries.machine-sub-type',[
                'machineSubTypes' => $machineSubTypes,
                'machineSubType' => $machineSubType,
                'machineCategories' => $machineCategories,
                'machineType' => $machineType,
                'machineTypes' => $machineTypes
            ]);
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
            return back();
        }
    }

    public function update(MachineSubTypeFormRequest $request, MachineSubType $machineSubType): RedirectResponse
    {
        try {
            $machineSubType->fill($request->all())->save();
            Session::flash('alert-success','Data Updated Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('machine-sub-type.index');
        }
    }

    public function destroy(MachineSubType $machineSubType): RedirectResponse
    {
        try {
            $machineSubType->delete();
            Session::flash('alert-danger','Data Deleted Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
