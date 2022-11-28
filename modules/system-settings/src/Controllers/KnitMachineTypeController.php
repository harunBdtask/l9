<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\MachineType;
use SkylarkSoft\GoRMG\SystemSettings\Requests\MachineTypeRequest;
use Throwable;

class KnitMachineTypeController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q ?? '';
        $machineType = MachineType::query()
            ->when($q, function ($query) use ($q) {
                $query->where('name', $q);
            })
            ->orderByDesc('id')
            ->paginate();

        return view('system-settings::pages.machine_type', compact('machineType'));
    }

    public function create()
    {
        $machineType = null;

        return view('system-settings::forms.machine_type', compact('machineType'));
    }

    /**
     * @throws Throwable
     */
    public function store(MachineTypeRequest $request): RedirectResponse
    {
        $id = $request->id ?? '';

        try {
            DB::beginTransaction();
            $machineType = MachineType::query()->findOrNew($id);
            $machineType->fill($request->all())->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Data Stored Failed!!');
        }

        return redirect('/knit-machine-types');
    }

    public function edit($id)
    {
        $machineType = MachineType::query()->find($id);

        return view('system-settings::forms.machine_type', compact('machineType'));
    }

    /**
     * @throws Throwable
     */
    public function destroy($id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $machineTypes = MachineType::query()->findOrFail($id);
            $machineTypes->delete();
            DB::commit();
            Session::flash('alert-success', 'Data Deleted Successfully!!');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');
        }

        return redirect()->back();
    }
}
