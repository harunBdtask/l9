<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\DyeingMachine;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\Subcontract\Requests\Libraries\DyeingMachineFormRequest;

class DyeingMachineController extends Controller
{
    protected function dyeingMachine(Request $request): LengthAwarePaginator
    {
        return DyeingMachine::query()
            ->when($request->get('floor_type_filter'), function ($query) use ($request) {
                $query->where('floor_type', "{$request->get('floor_type_filter')}");
            })
            ->when($request->get('machine_name_filter'), function ($query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->get('machine_name_filter')}%");
            })
            ->when($request->get('machine_type_filter'), function ($query) use ($request) {
                $query->where('type', 'LIKE', "%{$request->get('machine_type_filter')}%");
            })
            ->when($request->get('machine_status_filter'), function ($query) use ($request) {
                $query->where('status', 'LIKE', "%{$request->get('machine_status_filter')}%");
            })->paginate();
    }

    public function index(Request $request)
    {
        $dyeingMachines = $this->dyeingMachine($request);
        $floorTypes = collect(DyeingMachine::FLOOR_TYPES)->prepend('Select', 0);
        $status = collect(DyeingMachine::STATUS)->prepend('Select', 0);
        $machineTypes = collect(DyeingMachine::MACHINE_TYPE)->prepend('Select');

        return view(PackageConst::VIEW_PATH.'libraries.dyeing_machine', [
            'dyeingMachine' => null,
            'floorTypes' => $floorTypes,
            'status' => $status,
            'machineTypes' => $machineTypes,
            'dyeingMachines' => $dyeingMachines,
        ]);
    }

    public function store(DyeingMachineFormRequest $request, DyeingMachine $dyeingMachine): RedirectResponse
    {
        //dd($request->all());
        try {
            $dyeingMachine->fill($request->all())->save();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('dyeing-machine.index');
        }
    }

    public function edit(Request $request, DyeingMachine $dyeingMachine)
    {
        try {
            $dyeingMachines = $this->dyeingMachine($request);
            $floorTypes = collect(DyeingMachine::FLOOR_TYPES)->prepend('Select', 0);
            $status = collect(DyeingMachine::STATUS)->prepend('Select', 0);
            $machineTypes = collect(DyeingMachine::MACHINE_TYPE)->prepend('Select');

            return view(PackageConst::VIEW_PATH.'libraries.dyeing_machine', [
                'dyeingMachine' => $dyeingMachine,
                'dyeingMachines' => $dyeingMachines,
                'floorTypes' => $floorTypes,
                'status' => $status,
                'machineTypes' => $machineTypes,
            ]);
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());

            return back();
        }
    }

    public function update(DyeingMachineFormRequest $request, DyeingMachine $dyeingMachine): RedirectResponse
    {
        try {
            $dyeingMachine->fill($request->all())->save();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('dyeing-machine.index');
        }
    }

    public function destroy(DyeingMachine $dyeingMachine)
    {
        try {
            $dyeingMachine->delete();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
