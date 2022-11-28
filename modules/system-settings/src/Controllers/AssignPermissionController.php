<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\AssignPermission;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Menu;
use SkylarkSoft\GoRMG\SystemSettings\Models\Module;
use SkylarkSoft\GoRMG\SystemSettings\Models\Permission;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Requests\AssignModuleWiseFullPermissionRequest;
use SkylarkSoft\GoRMG\SystemSettings\Requests\AssignPermissionRequest;

class AssignPermissionController extends Controller
{
    public function index()
    {
        $users = User::where('email', '!=', 'super@skylarksoft.com')->orderBy('id', 'DESC')->with('departmnt')->paginate();

        return view('system-settings::pages.assign_permissions', ['users' => $users]);
    }

    public function create()
    {
        $factories = Factory::pluck('factory_name', 'id')->all();
        $modules = Module::withoutGlobalScope('factoryId')->pluck('module_name', 'id')->all();

        return view('system-settings::forms.assign_permission', [
            'factories' => $factories,
            'modules' => $modules,
        ]);
    }

    public function store(AssignPermissionRequest $request)
    {
        $assign_permission = AssignPermission::firstOrNew([
            'user_id' => $request->get('user_id'),
            'module_id' => $request->get('module_id'),
            'menu_id' => $request->get('menu_id'),
        ]);

        $assign_permission->user_id = $request->get('user_id');
        $assign_permission->module_id = $request->get('module_id');
        $assign_permission->menu_id = $request->get('menu_id');
        $assign_permission->factory_id = $request->get('factory_id');
        $assign_permission->permissions = implode(',', $request->get('permission_id'));
        $is_assign = $assign_permission->save();
        if ($is_assign) {
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('assign-permissions');
        }
    }

    public function getMenus($moduleId)
    {
        return Menu::where('module_id', $moduleId)->get();
    }

    public function edit($id)
    {
        $assign_permission = AssignPermission::findOrFail($id);
        $permissions = Permission::get();

        return view('system-settings::forms.assign_permission_edit', [
            'assign_permission' => $assign_permission,
            'permissions' => $permissions,
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'factory_id' => 'required',
            'user_id' => 'required',
            'menu_id' => 'required',
            'module_id' => 'required',
            'permission_id' => 'required|array|min:1',
        ], [
            'factory_id.required' => 'Factory is required',
            'user_id.required' => 'User field is required.',
            'menu_id.required' => 'Menu field is required.',
            'permission_id.required' => 'Select at least one permission.',
            'permission_id.array' => 'Select at least one permission.',
            'permission_id.min' => 'Select at least one permission.',
        ]);

        try {
            DB::beginTransaction();
            $user_id = $request->get('user_id');
            $permission_ids = $request->get('permission_id');
            foreach ($permission_ids as $menu_id => $permission_id) {
                $assign_permission = AssignPermission::firstOrNew([
                    'user_id' => $user_id,
                    'module_id' => $request->get('module_id'),
                    'menu_id' => $menu_id,
                ]);
                $assign_permission->user_id = $user_id;
                $assign_permission->module_id = $request->get('module_id');
                $assign_permission->menu_id = $menu_id;
                $assign_permission->factory_id = $request->get('factory_id');
                $assign_permission->permissions = implode(',', $permission_id);
                $assign_permission->save();
            }
            DB::commit();
            $html = view('partials.flash_message', [
                'message_class' => "success",
                'message' => "Permission Assigned Successfully!!",
            ])->render();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'message' => $html,
            ]);
        } catch (Exception $e) {
            DB::rollback();
            $html = view('partials.flash_message', [
                'message_class' => "danger",
                'message' => "Something went wrong!!",
            ])->render();

            return response()->json([
                'status' => 'danger',
                'errors' => $e->getMessage(),
                'message' => $html,
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $role_permission = AssignPermission::findOrFail($id);
            $role_permission->delete();
            Session::flash('alert-success', 'Menu permission removed successfully!!');

            return redirect()->back();
        } catch (Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!!');

            return redirect()->back();
        }
    }

    public function viewAssignedMenus($user_id)
    {
        $module_name = request('module_name') ?? null;
        $menu_name = request('menu_name') ?? null;
        $module_id = null;
        if ($module_name) {
            $module_query = Module::query()->withoutGlobalScope('factoryId')->where('module_name', $module_name)->first();
            $module_id = $module_query ? $module_query->id : null;
        }
        $menu_ids = null;
        if ($menu_name) {
            $menu_query = Menu::query()->withoutGlobalScope('factoryId')->where('menu_name', $menu_name)->first();
            $menu_ids = $menu_query ? [$menu_query->id] : [];
            $submodule_ids = ($menu_ids && \is_array($menu_ids) && count($menu_ids)) ? Menu::query()->withoutGlobalScope('factoryId')->whereIn('submodule_id', $menu_ids)->pluck('id')->toArray() : [];
            $menu_ids = \array_unique(array_merge($menu_ids, $submodule_ids));
        }
        $user_permissions = AssignPermission::where('user_id', $user_id)
        ->with('user', 'menu')
        ->when($module_id, function ($query) use ($module_id) {
            $query->where('module_id', $module_id);
        })
        ->when(($menu_ids && \is_array($menu_ids) && count($menu_ids)), function ($query) use ($menu_ids) {
            $query->whereIn('menu_id', $menu_ids);
        })
        ->orderBy('module_id')
        ->paginate();

        return view('system-settings::pages.user_menu_permissions', [
            'user_id' => $user_id,
            'user_permissions' => $user_permissions
        ]);
    }

    public function userAssignedMenuDelete($id)
    {
        $menu = AssignPermission::findorFail($id);
        if ($menu->delete()) {
            $status = SUCCESS;
        } else {
            $status = FAIL;
        }

        return $status;
    }

    public function getMenuWisePermissionForm($module_ids)
    {
        try {
            $menus = Menu::withoutGlobalScope('factoryId')->orderBy('submodule_id')->whereIn('module_id', explode(',', $module_ids))->get();
            $permissions = Permission::withoutGlobalScope('factoryId')->select('permission_name', 'id')->get();
            $html = view('system-settings::forms.menu_wise_permission_form', [
                'menus' => $menus,
                'permissions' => $permissions,
            ])->render();

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'html' => $html,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'danger',
                'errors' => $e->getMessage(),
                'message' => "Something went wrong!!",
            ]);
        }
    }

    public function assignModuleWisePermission(Request $request)
    {
        $request->validate([
            'factory_id' => 'required',
            'user_id' => 'required|array|min:1',
            'menu_id' => 'required|array|min:1',
            'module_ids' => 'required|array',
            'permission_id' => 'required|array|min:1',
        ], [
            'factory_id.required' => 'Factory is required',
            'user_id.required' => 'User field is required.',
            'user_id.array' => 'User field is required.',
            'user_id.min' => 'User field is required.',
            'menu_id.required' => 'Menu field is required.',
            'menu_id.array' => 'Menu field is required.',
            'menu_id.min' => 'Menu field is required.',
            'permission_id.required' => 'Select at least one permission.',
            'permission_id.array' => 'Select at least one permission.',
            'permission_id.min' => 'Select at least one permission.',
        ]);

        try {
            DB::beginTransaction();
            $user_ids = $request->user_id;
            $permission_ids = $request->permission_id;
            foreach ($user_ids as $user_id) {
                foreach ($permission_ids as $module_id => $permissions) {
                    foreach ($permissions as $menu_id => $permission_id) {
                        $assign_permission = AssignPermission::firstOrNew([
                            'user_id' => $user_id,
                            'module_id' => $module_id,
                            'menu_id' => $menu_id,
                        ]);
                        $assign_permission->user_id = $user_id;
                        $assign_permission->module_id = $module_id;
                        $assign_permission->menu_id = $menu_id;
                        $assign_permission->factory_id = $request->factory_id;
                        $assign_permission->permissions = implode(',', $permission_id);
                        $assign_permission->save();
                    }
                }
            }
            DB::commit();
            $html = view('partials.flash_message', [
                'message_class' => "success",
                'message' => "Permission Assigned Successfully!!",
            ])->render();

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'message' => $html,
            ]);
        } catch (Exception $e) {
            DB::rollback();
            $html = view('partials.flash_message', [
                'message_class' => "danger",
                'message' => "Something went wrong!!",
            ])->render();

            return response()->json([
                'status' => 'danger',
                'errors' => $e->getMessage(),
                'message' => $html,
            ]);
        }
    }

    public function assignModuleWiseFullPermission()
    {
        $factories = Factory::pluck('factory_name', 'id')->all();
        $modules = Module::withoutGlobalScope('factoryId')->orderBy('module_name', 'asc')->pluck('module_name', 'id')->all();

        return view('system-settings::forms.assign_module_wise_full_permission', [
            'assign_permission' => null,
            'factories' => $factories,
            'modules' => $modules,
        ]);
    }

    public function assignModuleWiseFullPermissionStore(AssignModuleWiseFullPermissionRequest $request)
    {
        try {
            $permissions = Permission::all()->pluck('id');
            $permission_arr = [];
            foreach ($permissions as $key => $permission) {
                $permission_arr[$key] = $permission;
            }
            $permission_string = implode(',', $permission_arr);
            $menu_query = Menu::withoutGlobalScope('factoryId')->whereIn('module_id', $request->module_id);
            if (!$menu_query->count()) {
                $html = view('partials.flash_message', [
                    'message_class' => "danger",
                    'message' => "Module does not have any menu!",
                ])->render();

                return response()->json([
                    'status' => 'danger',
                    'errors' => null,
                    'message' => $html,
                ]);
            } else {
                DB::beginTransaction();

                $menus = $menu_query->get();
                $user_ids = $request->user_id;
                foreach ($user_ids as $user_id) {
                    foreach ($menus as $menu) {
                        $assign_permission = AssignPermission::firstOrNew([
                            'user_id' => $user_id,
                            'menu_id' => $menu->id,
                        ]);
                        $assign_permission->user_id = $user_id;
                        $assign_permission->module_id = $menu->module_id;
                        $assign_permission->menu_id = $menu->id;
                        $assign_permission->factory_id = $request->factory_id;
                        $assign_permission->permissions = $permission_string;
                        $assign_permission->save();
                    }
                }
                DB::commit();
                $html = view('partials.flash_message', [
                    'message_class' => "success",
                    'message' => "Permission Assigned Successfully!!",
                ])->render();

                return response()->json([
                    'status' => 'success',
                    'errors' => null,
                    'message' => $html,
                ]);
            }
        } catch (Exception $e) {
            DB::rollback();
            $html = view('partials.flash_message', [
                'message_class' => "danger",
                'message' => "Something went wrong!!",
            ])->render();

            return response()->json([
                'status' => 'danger',
                'errors' => $e->getMessage(),
                'message' => $html,
            ]);
        }
    }

    public function removeModuleWiseFullPermissionStore(AssignModuleWiseFullPermissionRequest $request)
    {
        try {
            DB::beginTransaction();
            $factory_id = $request->factory_id;
            $user_ids = $request->user_id;
            $module_ids = $request->module_id;
            AssignPermission::query()
                ->whereIn('user_id', $user_ids)
                ->whereIn('module_id', $module_ids)
                ->forceDelete();
            DB::commit();
            $html = view('partials.flash_message', [
                'message_class' => "success",
                'message' => "Permission removed successfully!!",
            ])->render();

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'message' => $html,
            ]);
        } catch (Exception $e) {
            DB::rollback();
            $html = view('partials.flash_message', [
                'message_class' => "danger",
                'message' => "Something went wrong!!",
            ])->render();

            return response()->json([
                'status' => 'danger',
                'errors' => $e->getMessage(),
                'message' => $html,
            ]);
        }
    }

    public function userPermissionSearch(Request $request)
    {
        $query_string = $request->get('query_string');
        $column_name = $request->get('column_name');
        $query = User::where('email', '!=', 'super@skylarksoft.com')->orderBy('id', 'DESC')->with('departmnt');
        if (!$query_string) {
            return redirect('/assign-permissions');
        }
        switch ($column_name) {
            case "user_name":
                $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE '%".$query_string."%'");

                break;
            case "email":
                $query->where('email', 'LIKE', "%$query_string%");

                break;
            case "department":
                $query->whereHas('departmnt', function ($q) use ($query_string) {
                    $q->where('department_name', 'LIKE', "%$query_string%");
                });

                break;
            default:
                Session::flash('alert-danger', 'Please Select Column For Search');

                return redirect('assign-permissions')->withInput();
        }
        $users = $query->paginate();

        return view('system-settings::pages.assign_permissions', ['users' => $users]);
    }
}
