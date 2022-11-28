<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\SystemSettings\Menu;
use SkylarkSoft\GoRMG\SystemSettings\Module;
use SkylarkSoft\GoRMG\SystemSettings\Requests\SubmoduleRequest;
use SkylarkSoft\GoRMG\SystemSettings\Submodule;

class SubmoduleController extends Controller
{
    public function index()
    {
        $submodules = Submodule::orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.submodules', ['submodules' => $submodules]);
    }

    public function create()
    {
        $modules = Module::pluck('module_name', 'id')->all();

        return view('system-settings::forms.submodule', ['submodule' => null, 'modules' => $modules]);
    }

    public function store(SubmoduleRequest $request)
    {
        Submodule::create($request->all());

        return redirect('/submodules');
    }

    public function edit($id)
    {
        $modules = Module::pluck('module_name', 'id')->all();
        $submodule = Submodule::findOrFail($id);

        return view('system-settings::forms.submodule', ['submodule' => $submodule, 'modules' => $modules]);
    }

    public function update($id, SubmoduleRequest $request)
    {
        $submodule = Submodule::findOrFail($id);
        $submodule->update($request->all());

        return redirect('/submodules');
    }

    public function destroy($id)
    {
        $submodule = Submodule::findOrFail($id);
        $submodule->delete();

        return redirect('/submodules');
    }

    public function getSubmodules($module_id)
    {
        return Menu::where('module_id', $module_id)->pluck('menu_name', 'id')->all();
    }
}
