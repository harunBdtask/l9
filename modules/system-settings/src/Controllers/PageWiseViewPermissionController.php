<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\PageWiseViewPermission;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Services\ViewPagePermissionService;

class PageWiseViewPermissionController extends Controller
{
    public function index(Request $request)
    {   $search =$request->user_search_id ?? [] ;
        $data['companies'] = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        $data['pages'] = (new ViewPagePermissionService())->getPages()->pluck('name', 'id')->prepend("Select Page", '0');
        $data['permissions'] = PageWiseViewPermission::with('company')                                      
                                ->orderBy('id', 'desc')->get()->groupBy('user_id');
        if(!empty($search)){
            $data['permissions'] = PageWiseViewPermission::with('company')
                                ->whereHas('user', function ($query) use($search){
                                    $query->whereIn('id',$search);  
                                })                
                                ->orderBy('id', 'desc')->get()->groupBy('user_id');
        }
        $data['search'] = $search;
        return view('system-settings::page-wise-view-permission.create-update', $data);
    }

    public function store(Request $request)
    {
        try {
            $views = $request->input('view_id');
            $pages = $request->input('page_id');
            $users = $request->input('user_id');

            if (in_array('all', $request->input('user_id'))) {
                $users = User::getUsers($request->input('company_id'))->pluck('id');
            }

            foreach ($users as $user) {
                foreach ($views as $view) {
                    PageWiseViewPermission::query()
                        ->updateOrCreate(
                            [
                                'user_id' => $user,
                                'view_id' => $view
                            ],
                            [
                                'company_id' => $request->input('company_id'),
                                'page_id' => (new ViewPagePermissionService())->getPageByView($view)
                            ]
                        );
                }
            }

            Session::flash('success', S_SAVE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', E_SAVE_MSG);
        }

        return redirect('/page-wise-view-permission');
    }

    public function edit($id)
    {
        $pageWiseViews = PageWiseViewPermission::find($id);

        $data['pageWiseViews'] = $pageWiseViews;
        $data['selectedBuyer'] = explode(',', $pageWiseViews->buyer_id);
        $data['selectedView'] = explode(',', $pageWiseViews->view_id);

        $data['companies'] = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        $buyers = Buyer::get(['id', 'name']);
        $data['buyers'] = $buyers->pluck('name', 'id');
        $data['pages'] = (new ViewPagePermissionService())->getPages()->pluck('name', 'id')->prepend("Select Page", '0');
        $data['views'] = (new ViewPagePermissionService())->getViews($pageWiseViews->page_id)->pluck('name', 'id');
        $data['permissions'] = PageWiseViewPermission::with('company')->orderBy('id', 'desc')->get()->map(function ($item) use ($buyers) {
            return [
                'company' => $item['company']->factory_name ?? '',
                'buyer' => collect($buyers)->whereIn('id', explode(',', $item['buyer_id']))->pluck('name')->values()->implode(','),
                'page' => $item['page_id'],
                'view' => $item['view_id'],
                'id' => $item['id'],
            ];
        });


        return view('system-settings::page-wise-view-permission.render-page-wise-permission', $data)->render();
    }

    public function getViews(Request $request)
    {
        $pages = $request->get('pages');
        $views = [];
        if ($pages) {
            foreach ($pages as $page) {
                $views[] = (new ViewPagePermissionService())->getViews($page);
            }
        }

        return response(collect($views)->collapse());
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            PageWiseViewPermission::query()->where('user_id', $id)->delete();
            DB::commit();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('/page-wise-view-permission');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!! ERROR CODE: Company.D-102');

            return redirect()->back();
        }
    }

    public function update($id)
    {
        $pageWiseViews = PageWiseViewPermission::find($id);

        $data = request()->except('_token', 'buyer_id', 'view_id');
        $data['buyer_id'] = implode(',', request()->get('buyer_id'));
        $data['view_id'] = implode(',', request()->get('view_id'));

        try {
            $pageWiseViews->update($data);
            Session::flash('success', S_SAVE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', E_SAVE_MSG);
        }

        return redirect('/page-wise-view-permission');
    }

    public function deletePage($userId, $id)
    {
        try {
            DB::beginTransaction();
            PageWiseViewPermission::query()
                ->where('user_id', $userId)
                ->where('page_id', $id)
                ->delete();
            DB::commit();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('/page-wise-view-permission');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!! ERROR CODE: Company.D-102');

            return redirect()->back();
        }
    }

    public function deleteView($userId, $id)
    {
        try {
            DB::beginTransaction();
            PageWiseViewPermission::query()
                ->where('user_id', $userId)
                ->where('view_id', $id)
                ->delete();
            DB::commit();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('/page-wise-view-permission');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!! ERROR CODE: Company.D-102');

            return redirect()->back();
        }
    }
}
