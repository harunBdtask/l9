<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Models\UserCuttingFloorPlanPermission;

class UserCuttingFloorPlanPermissionController extends Controller
{
    public function index()
    {
        $factory_id = 0;
        if (getRole() != 'super-admin') {
            $factory_id = factoryId();
        }
        $cutting_floors_permission_query = CuttingFloor::withoutGlobalScopes()
            ->with('factory:id,factory_name')
            ->whereNull('cutting_floors.deleted_at')
            ->leftJoin('user_cutting_floor_plan_permissions', 'user_cutting_floor_plan_permissions.cutting_floor_id', 'cutting_floors.id')
            ->leftJoin('users', 'user_cutting_floor_plan_permissions.user_id', 'users.id')
            ->select('cutting_floors.*', 'users.email', 'user_cutting_floor_plan_permissions.user_id');
        if ($factory_id) {
            $cutting_floors_permission_query = $cutting_floors_permission_query->where('cutting_floors.factory_id', $factory_id);
        }
        $user_cutting_floor_permissions = $cutting_floors_permission_query->get();

        $user_cutting_floor_permissions_clone = clone $user_cutting_floor_permissions;

        foreach ($user_cutting_floor_permissions_clone as $key => $permission) {
            $user_cutting_floor_permissions[$key]['users'] = User::where('role_id', '!=', 1)->where('factory_id', $permission->factory_id)->pluck('email', 'id');
        }

        return view('system-settings::pages.user_cutting_floor_permissions', ['user_cutting_floor_permissions' => $user_cutting_floor_permissions]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $user_cutting_floor_plan_permission = UserCuttingFloorPlanPermission::firstOrNew(['cutting_floor_id' => $request->cutting_floor_id]);
            $user_cutting_floor_plan_permission->cutting_floor_id = $request->cutting_floor_id;
            $user_cutting_floor_plan_permission->user_id = $request->user_id;
            $user_cutting_floor_plan_permission->save();
            DB::commit();
            $message = 'Data Updated Successfully!';
            $type = 'success';
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $type = 'danger';
        }

        return response()->json([
            'message' => $message,
            'type' => $type,
        ]);
    }

    public function updateLockInfo(Request $request)
    {
        try {
            DB::beginTransaction();
            $user_cutting_floor_plan_permission = UserCuttingFloorPlanPermission::where(['cutting_floor_id' => $request->cutting_floor_id, 'user_id' => $request->user_id]);
            if ($user_cutting_floor_plan_permission->count()) {
                $user_cutting_floor_plan_permission->update([
                    'is_locked' => $request->is_locked,
                ]);
                $message = 'Data Updated Successfully!';
                $type = 'success';
            } else {
                if (getRole() == 'super-admin') {
                    $user_cutting_floor_plan_permission_new = UserCuttingFloorPlanPermission::firstOrNew(['cutting_floor_id' => $request->cutting_floor_id]);
                    $user_cutting_floor_plan_permission_new->cutting_floor_id = $request->cutting_floor_id;
                    $user_cutting_floor_plan_permission_new->user_id = $request->user_id;
                    $user_cutting_floor_plan_permission_new->is_locked = $request->is_locked;
                    $user_cutting_floor_plan_permission_new->save();
                    $message = 'Data Updated Successfully!';
                    $type = 'success';
                } else {
                    $message = 'You are not permitted!';
                    $type = 'danger';
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $type = 'danger';
        }

        return response()->json([
            'message' => $message,
            'type' => $type,
        ]);
    }

    public function getCuttingPlanUserPermission($cutting_floor_id, $user_id)
    {
        if (getRole() == 'super-admin') {
            $permission = 1;
        } else {
            $permission = UserCuttingFloorPlanPermission::where(['cutting_floor_id' => $cutting_floor_id, 'user_id' => $user_id])->count();
        }

        return $permission;
    }

    public function checkCuttingPlanBoardLock($cutting_floor_id)
    {
        $is_locked = UserCuttingFloorPlanPermission::where(['cutting_floor_id' => $cutting_floor_id, 'is_locked' => 1])->count();

        return response()->json(['is_locked' => $is_locked]);
    }
}
