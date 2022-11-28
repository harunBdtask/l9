<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\UserWiseBuyerPermission;

class UserWiseBuyerPermissionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $permissions = UserWiseBuyerPermission::query()
            ->with([
                'factory:id,factory_name',
                'buyer:id,name',
                'viewBuyer:id,name',
                'user:id,screen_name'
            ])
            ->orderBy('id', 'DESC')
            ->search($search)
            ->paginate();

        return view('system-settings::pages.user-wise-buyer-permission-list', compact('permissions'));
    }

    public function create()
    {
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id')->all();

        return view('system-settings::pages.user-wise-buyer-permission', [
            'factories' => $factories,
        ]);
    }

    public function store(Request $request)
    {
        $checkAllBuyer = $request->get('buyer_id')[0] ?? null;
        $checkAllViewBuyer = $request->get('view_buyer_id')[0] ?? null;
        $buyers = $checkAllBuyer == 'all_buyer' ? Buyer::query()->pluck('id') : $request->get('buyer_id');
        $viewBuyers = $checkAllViewBuyer == 'all_buyer' ? Buyer::query()->pluck('id') : $request->get('view_buyer_id');


        if ($buyers) {
            foreach ($buyers as $item) {
                $userWiseBuyer = new UserWiseBuyerPermission();
                $userWiseBuyer->factory_id = $request->get('factory_id');
                $userWiseBuyer->user_id = $request->get('user_id');
                $userWiseBuyer->buyer_id = $item;
                $userWiseBuyer->permission_type = UserWiseBuyerPermission::BUYER_PERMISSION;
                $userWiseBuyer->buyer_permission_type = $checkAllBuyer == 'all_buyer' ? 'all_buyer' : null;
                $userWiseBuyer->save();
            }
        }

        if ($viewBuyers) {
            foreach ($viewBuyers as $item) {
                $userWiseBuyer = new UserWiseBuyerPermission();
                $userWiseBuyer->factory_id = $request->get('factory_id');
                $userWiseBuyer->user_id = $request->get('user_id');
                $userWiseBuyer->view_buyer_id = $item;
                $userWiseBuyer->permission_type = UserWiseBuyerPermission::VIEW_BUYER_PERMISSION;
                $userWiseBuyer->view_buyer_permission_type = $checkAllViewBuyer == 'all_buyer' ? 'all_buyer' : null;
                $userWiseBuyer->save();
            }
        }

        return redirect('/user-wise-buyer-permission-list');
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $permission = UserWiseBuyerPermission::findOrFail($id);
            $permission->delete();
            DB::commit();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('/user-wise-buyer-permission-list');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!! ERROR CODE: Company.D-102');

            return redirect()->back();
        }
    }
}
