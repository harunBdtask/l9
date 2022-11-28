<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Role;
use SkylarkSoft\GoRMG\SystemSettings\Requests\RoleRequest;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.roles', ['roles' => $roles, 'role' => null]);
    }

    public function store(RoleRequest $request)
    {
        Session::flash('alert-success', 'Data stored successfully!!');
        Role::create([ 'name' => $request->get('name'), 'slug' => str_slug($request->get('name'))]);

        return redirect('/roles');
    }

    public function edit($id)
    {
        return Role::findOrFail($id);
    }

    public function update($id, RoleRequest $request)
    {
        $role = Role::findOrFail($id);
        $role->update([ 'name' => $request->name, 'slug' => str_slug($request->name)]);
        Session::flash('alert-success', 'Data Updated successfully!!');

        return redirect('/roles');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        Session::flash('alert-success', 'Data Deleted successfully!!');

        return redirect('/roles');
    }
}
