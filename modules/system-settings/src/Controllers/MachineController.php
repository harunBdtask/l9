<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\SystemSettings\Models\KnittingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Machine;
use SkylarkSoft\GoRMG\SystemSettings\Models\MachineType;
use SkylarkSoft\GoRMG\SystemSettings\Requests\MachineRequest;
use SkylarkSoft\GoRMG\SystemSettings\Services\MachineTypeService;
use Throwable;

class MachineController extends Controller
{
    public function index()
    {
        $data['machines'] = Machine::query()
            ->with('knittingFloor')
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return view('system-settings::pages.machines', $data);
    }

    public function create()
    {
        $data['machine'] = null;
        $data['machineType'] = MachineTypeService::all()->map(function ($type) {
            return [$type['id'] => $type['text']];
        })->collapse();

        $knittingFloor = KnittingFloor::query()->get();

        $data['knitting_floor'] = ['' => 'Select'];
        foreach ($knittingFloor as $key => $value) {
            $data['knitting_floor'][$value->id] = $value->name;
        }

        return view('system-settings::forms.machine', $data);
    }

    /**
     * @throws Throwable
     */
    public function store(MachineRequest $request)
    {
        try {
            DB::beginTransaction();
            $machine = new Machine();
            $machine->fill($request->all())->save();
            DB::commit();
            Session::flash('alert-success', 'This Machine Added Successfully!!');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-success', 'This Machine Added Failed!! ErrorCode : MAF : 101');
        }

        return redirect('machines');
    }

    public function edit($id)
    {
        $data['machine'] = Machine::query()->find($id);
        $data['machineType'] = MachineTypeService::all()->map(function ($type) {
            return [$type['id'] => $type['text']];
        })->collapse();

        $knittingFloor = KnittingFloor::query()->get();
        $data['knitting_floor'] = ['' => 'Select'];
        foreach ($knittingFloor as $key => $value) {
            $data['knitting_floor'][$value->id] = $value->name;
        }

        return view('system-settings::forms.machine', $data);
    }

    /**
     * @throws Throwable
     */
    public function update($id, MachineRequest $request)
    {
        try {
            DB::beginTransaction();
            $machine = Machine::query()->findOrFail($id);
            $machine->fill($request->all())->save();
            $machine->save();
            DB::commit();
            Session::flash('alert-success', 'This Machine Updated Successfully!!');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'This Machine update Failed!!');
        }

        return redirect('machines');
    }

    /**
     * @throws Throwable
     */
    public function delete($id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $machine = Machine::query()->findOrFail($id);
            $machine->delete();
            DB::commit();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');
        }

        return redirect()->back();
    }

    public function getMachineInfoAjax($id): JsonResponse
    {
        $query = Machine::query()->where('id', $id);
        if ($query->count() > 0) {
            $machine = $query->first()->machine_dia;
            $status = 200;
        } else {
            $machine = [];
            $status = 500;
        }

        return response()->json(['status' => $status, 'machine' => $machine]);
    }

    public function getMachinesForKnitcard($factory_id, $machine_dia = '')
    {
        try {
            $machines = Machine::withoutGlobalScope('factoryId')
                ->selectRaw('CONCAT(machine_no,"[ ",machine_dia," ]") as machine , id')
                ->where([
                    'factory_id' => $factory_id,
                    'machine_type' => KNITTING_MACHINE_TYPE,
                ])
                ->when($machine_dia != '', function ($query) use ($machine_dia) {
                    return $query->where('machine_dia', $machine_dia);
                })
                ->pluck('machine', 'id');

            return response()->json([
                'status' => 'success',
                'list' => $machines,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage(),
                'list' => null,
            ]);
        }
    }

    public function getMachineRpm($id)
    {
        try {
            $machine_rpm = Machine::withoutGlobalScope('factoryId')
                ->findOrFail($id)
                ->machine_rpm;

            return response()->json([
                'status' => 'success',
                'machine_rpm' => $machine_rpm,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage(),
                'machine_rpm' => null,
            ]);
        }
    }

    public function getKnittingMachinesForSingleFactory($factory_id)
    {
        try {
            $machines = Machine::withoutGlobalScope('factoryId')
                ->where('factory_id', $factory_id)
                ->where('machine_type', KNITTING_MACHINE_TYPE)
                ->pluck('machine_no', 'id');

            return response()->json([
                'status' => 'success',
                'list' => $machines,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage(),
                'list' => null,
            ]);
        }
    }
}
