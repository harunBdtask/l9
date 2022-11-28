<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\AssignPermission;
use SkylarkSoft\GoRMG\SystemSettings\Models\Permission;
use SkylarkSoft\GoRMG\SystemSettings\Requests\PermissionRequest;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::query()
            ->when($request->get('q'), function ($query) use ($request) {
                $query->where('permission_name', 'LIKE', "%{$request->get('q')}%");
            })
            ->orderBy('id', 'desc')
            ->paginate();

        return view('system-settings::pages.permissions', ['permissions' => $permissions, 'permission' => null]);
    }

    public function store(PermissionRequest $request)
    {
        Permission::query()
            ->create($request->all());
        Session::flash('alert-success', 'Data Created successfully!!');

        return redirect('/permissions');
    }

    public function edit($id)
    {
        return Permission::query()
            ->findOrFail($id);
    }

    public function update($id, PermissionRequest $request)
    {
        $permission = Permission::query()
            ->findOrFail($id);
        Session::flash('alert-success', 'Data Updated successfully!!');
        $permission->update($request->all());

        return redirect('/permissions');
    }

    public function destroy($id)
    {
        $permission = Permission::query()
            ->findOrFail($id);
        $permission->delete();
        Session::flash('alert-danger', 'Data deleted successfully!!');

        return redirect('/permissions');
    }

    public function getAllPermissionsByMenu(Request $request)
    {
        $query = AssignPermission::query()
            ->where([
                'user_id' => $request->get('user_id'),
                'module_id' => $request->get('module_id'),
                'menu_id' => $request->get('menu_id')]);
        $all_permissions = Permission::query()->get();
        $html = '';
        if ($query->count() > 0) {
            $permissions = $query->first()->permissions;
            $permission_array = explode(',', $permissions);
            foreach ($all_permissions as $permission) {
                if (in_array($permission->id, $permission_array)) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $html .= '<div class="col-md-6">';
                $html .= '<label class="container-2">' . $permission->permission_name;
                $html .= '<input type="checkbox" value="' . $permission->id . '" name="permissions[]"' . $checked . '>';
                $html .= '<span class="checkmark"></span> </label> </div>';
            }
            echo $html;
            exit;
        } else {
            foreach ($all_permissions as $permission) {
                $html .= '<div class="col-md-6">';
                $html .= '<label class="container-2">' . $permission->permission_name;
                $html .= '<input type="checkbox" value="' . $permission->id . '" name="permissions[]">';
                $html .= '<span class="checkmark"></span> </label> </div>';
            }
            echo $html;
            exit;
        }
    }
}
