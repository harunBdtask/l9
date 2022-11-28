<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\MachineType;
use SkylarkSoft\GoRMG\SystemSettings\Requests\MachineTypeRequest;

class MachineTypeController extends Controller
{
    public function index()
    {
        $machine_types = MachineType::orderBy('id', 'desc')->paginate();

        return view('system-settings::iedroplets.machine_types', ['machine_types' => $machine_types]);
    }

    public function create()
    {
        return view('system-settings::iedroplets.machine_type', ['machine_type' => null]);
    }

    public function store(MachineTypeRequest $request)
    {
        try {
            MachineType::create($request->all());
            Session::flash('success', S_SAVE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect('/machine-types');
    }

    public function edit($id)
    {
        $machine_type = MachineType::findOrFail($id);

        return view('system-settings::iedroplets.machine_type', ['machine_type' => $machine_type]);
    }

    public function update($id, MachineTypeRequest $request)
    {
        try {
            $machine_type = MachineType::findOrFail($id);
            $machine_type->update($request->all());

            Session::flash('success', S_UPDATE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect('/machine-types');
    }

    public function destroy($id)
    {
        try {
            $task = MachineType::findOrFail($id);
            $task->delete();

            Session::flash('success', S_DELETE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect('machine-types');
    }

    public function searchMachineType(Request $request)
    {
        $machine_types = MachineType::where('name', 'like', '%' . $request->q . '%')
            ->paginate();

        return view('system-settings::iedroplets.machine_types', ['machine_types' => $machine_types, 'q' => $request->q]);
    }
}
