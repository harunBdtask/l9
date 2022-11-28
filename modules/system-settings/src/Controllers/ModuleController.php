<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Module;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ModuleRequest;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        $modules = Module::query()
            ->withoutGlobalScope('factoryId')
            ->when($q != '', function ($query) use ($q) {
                return $query->where('module_name', 'like', '%' . $q . '%');
            })
            ->orderBy('module_name', 'asc')
            ->paginate(10);

        return view('system-settings::pages.modules', ['modules' => $modules, 'module' => null]);
    }

    public function store(ModuleRequest $request)
    {
        Module::create($request->all());
        Session::flash('alert-success', 'Data Created successfully');

        return redirect('/modules-data');
    }

    public function edit($id)
    {
        return Module::withoutGlobalScope('factoryId')->findOrFail($id);
    }

    public function update($id, ModuleRequest $request)
    {
        $module = Module::withoutGlobalScope('factoryId')->findOrFail($id);
        $module->update($request->all());
        Session::flash('alert-success', 'Data Updated successfully');

        return redirect('/modules-data');
    }

    public function destroy($id)
    {
        $module = Module::findOrFail($id);
        if ($module->assignPermissions()->count()) {
            Session::flash('alert-danger', "Cannot delete because some users are already assigned to this module!!");

            return redirect()->back();
        }
        $module->delete();
        Session::flash('alert-danger', 'Data Deleted successfully!!');

        return redirect('/modules-data');
    }
}
