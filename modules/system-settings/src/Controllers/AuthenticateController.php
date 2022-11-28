<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\McInventory\Services\MachineDateNotificationService;
use SkylarkSoft\GoRMG\SystemSettings\Actions\LocalizationCacheAction;
use SkylarkSoft\GoRMG\SystemSettings\Models\AssignPermission;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsProductionEntry;
use SkylarkSoft\GoRMG\SystemSettings\Models\Permission;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Requests\PostLogin;
use SkylarkSoft\GoRMG\SystemSettings\Services\AuthenticatedMenuViewStatus;
use SkylarkSoft\GoRMG\SystemSettings\Services\MenuView\AccountMenu;
use SkylarkSoft\GoRMG\SystemSettings\Services\MenuView\HrMenu;
use SkylarkSoft\GoRMG\SystemSettings\Services\MenuView\SideBarService;
use SkylarkSoft\GoRMG\SystemSettings\Services\PostLoginService;
use SkylarkSoft\GoRMG\TimeAndAction\Services\TNANotificationService;

class AuthenticateController extends Controller
{
    public function login()
    {
        $company_logo = null;
        $company = DB::table('companies')->first();
        $company_logo = $company ? $company->company_logo : null;
        session()->put('getCompanyLogo', $company_logo);

        return view('skeleton::login2');
    }

    public function postLogin(PostLogin $request)
    {
        $input = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];

        try {
            if (Auth::attempt($input)) {
                $user_role = DB::table('roles')
                    ->whereNull('deleted_at')
                    ->where('id', Auth::user()->role_id)
                    ->first()->slug ?? null;

                $dept_name = DB::table('departments')
                    ->whereNull('deleted_at')
                    ->where('id', Auth::user()->department)
                    ->first()->slug ?? '';

                Session::put('user_role', $user_role);
                Session::put('dept_name', $dept_name);

                User::find(Auth::user()->id)->update([
                    'status' => true,
                    'last_login' => Carbon::now(),
                ]);

                // Get user permission
                $modules = AssignPermission::select('assign_permissions.*', 'modules.id as module_id', 'modules.module_name')
                    ->leftJoin('modules', 'modules.id', '=', 'assign_permissions.module_id')
                    ->orderBy('modules.sort', 'asc')
                    ->where('user_id', Auth::user()->id)->get();
                $modules_array = [];
                $module_menu_routes = [];
                $user = Auth::user()->id;
                foreach ($modules as $moduleKey => $module) {
                    $menus = DB::select("select assign_permissions.*,menus.submodule_id,menus.id,COALESCE(menus.display_as, menus.menu_name) as menu_name, menus.menu_url
                                        from assign_permissions
                                        left join menus on menus.id = assign_permissions.menu_id
                                        where menus.left_menu = 1 and assign_permissions.module_id = '$module->module_id' and assign_permissions.deleted_at IS NULL and assign_permissions.user_id = '$user' ORDER BY menus.sort asc");
                    foreach ($menus as $menuKey => $menu) {
                        if (isset($menu->submodule_id)) {
                            continue;
                        }
                        $modules_array[$module->module_name][$menuKey]['menu_name'] = $menu->menu_name;
                        $modules_array[$module->module_name][$menuKey]['menu_url'] = $menu->menu_url;
                        $sub_menus = DB::table('menus')->select('menus.id as submenu_id', DB::raw('COALESCE(menus.display_as,menus.menu_name) as submenu_name'), 'menus.menu_url as submenu_url', 'menus.sort as sort')
                            ->leftJoin('assign_permissions', 'assign_permissions.menu_id', '=', 'menus.id')
                            ->where('menus.left_menu', 1)
                            ->where('menus.submodule_id', $menu->id)
                            ->where('assign_permissions.user_id', Auth::user()->id)
                            ->where('assign_permissions.deleted_at', null)
                            ->orderBy('menus.sort', 'asc')
                            ->get();

                        $modules_array[$module->module_name][$menuKey]['submodule_menu'] = $sub_menus;
                    }
                }

                $i = 0;
                foreach ($modules as $moduleKey => $module) {
                    $menus = DB::select("select assign_permissions.*,menus.submodule_id,menus.id,menus.menu_name,menus.menu_url
                                        from assign_permissions
                                        left join menus on menus.id = assign_permissions.menu_id
                                        where assign_permissions.module_id = '$module->module_id' and assign_permissions.deleted_at IS NULL and assign_permissions.user_id = '$user' ");
                    foreach ($menus as $menuKey => $menu) {
                        if (in_array(str_slug($menu->menu_url), $module_menu_routes)) {
                            continue;
                        }
                        $module_menu_routes[$i] = str_slug($menu->menu_url);
                        $i++;
                        foreach (explode(',', rtrim($menu->permissions, ',')) as $permissionKey => $permission) {
                            $hasPermission = Permission::where(['id' => $permission]);
                            if ($hasPermission->count() > 0) {
                                $permissionName = $hasPermission->first()->permission_name;
                            }
                            Session::put('permission_of_' . strtolower(str_replace(' ', '_', $menu->menu_name) . '_' . str_replace(' ', '_', $permissionName)), $permissionName);
                        }
                    }
                }

                Session::put('factoryId', Auth::user()->factory_id);
                Session::put('dashboardVersion', Auth::user()->dashboard_version);
                $factory = DB::table('factories')->where('id', Auth::user()->factory_id)->first();
                Session::put('factory_image', $factory ? $factory->factory_image : null);
                Session::put('factory_address', $factory ? preg_replace('/[^A-Za-z0-9\-,. ]/', '', $factory->factory_address) : null);
                Session::put('permission_details', $modules_array);
                Session::put('all_assigned_url_routes', $module_menu_routes);
                Session::put('menu_count', $i);
                $getProductionEntryData = $this->getProductionEntryData();
                $production_entry_type = $getProductionEntryData['production_entry_type'];
                $erp_menu_view_status = $getProductionEntryData['erp_menu_view_status'];
                $style_filter_option = $getProductionEntryData['style_filter_option'];
                $bundle_card_serial = $getProductionEntryData['bundle_card_serial'];
                $bundle_straight_serial_max_limit = $getProductionEntryData['bundle_straight_serial_max_limit'];
                $bundle_card_suffix_style = $getProductionEntryData['bundle_card_suffix_style'];
                $bundle_card_print_style = $getProductionEntryData['bundle_card_print_style'];
                $line_wise_hour_show = $getProductionEntryData['line_wise_hour_show'];
                $scan_data_caching_time = $getProductionEntryData['scan_data_caching_time'];
                $bundle_card_sticker_ratio_view_status = $getProductionEntryData['bundle_card_sticker_ratio_view_status'];
                $cutting_qty_validation = $getProductionEntryData['cutting_qty_validation'];
                $fabric_cons_approval = $getProductionEntryData['fabric_cons_approval'];
                $max_bundle_qty = $getProductionEntryData['max_bundle_qty'];
                $finishing_report = $getProductionEntryData['finishing_report'];

                Session::put('production_entry_type', $production_entry_type);
                Session::put('erp_menu_view_status', $erp_menu_view_status);
                Session::put('style_filter_option', $style_filter_option);
                Session::put('bundle_card_serial', $bundle_card_serial);
                Session::put('bundle_straight_serial_max_limit', $bundle_straight_serial_max_limit);
                Session::put('bundle_card_suffix_style', $bundle_card_suffix_style);
                Session::put('bundle_card_print_style', $bundle_card_print_style);
                Session::put('line_wise_hour_show', $line_wise_hour_show);
                Session::put('scan_data_caching_time', $scan_data_caching_time);
                Session::put('bundle_card_sticker_ratio_view_status', $bundle_card_sticker_ratio_view_status);
                Session::put('cutting_qty_validation', $cutting_qty_validation);
                Session::put('fabric_cons_approval', $fabric_cons_approval);
                Session::put('max_bundle_qty', $max_bundle_qty);
                Session::put('finishing_report', $finishing_report);
                (new LocalizationCacheAction())->execute();

                $admin_menus = $this->getAdminMenus();
                Session::put('menu', $admin_menus);

                $inactive_urls = $this->getInactiveUrls($erp_menu_view_status);
                Session::put('inactive_urls', $inactive_urls);

                SideBarService::using(new HrMenu())
                    ->mergeModifiedMenus()
                    ->putSession();

                if (!Cache::has('onlineUsers')) {
                    $onlineUsers = User::where('status', true)
                        ->whereDate('last_login', Carbon::today()->toDateString())
                        ->get();
                    $offlineUsers = User::where('status', false)
                        ->get();
                    Cache::put('onlineUsers', $onlineUsers, 1440);
                    Cache::put('offlineUsers', $offlineUsers, 1440);
                }
                // for todays column update
                if (!Cache::has('isAlreadyLogin')) {
                    Cache::put('isAlreadyLogin', true, 1440);
                }
                // for factory dropdown od header
                if (!Cache::has('factories')) {
                    Factory::getFactories();
                }
                Session::put('factories', Factory::getFactories());

                $company = DB::table('companies')->first();
                $company_logo = $company ? $company->company_logo : null;
                session()->put('getCompanyLogo', $company_logo);

                // Post Login Service
                PostLoginService::setBuyerPermission();
                PostLoginService::setViewBuyerPermission();
                PostLoginService::setPageWiseViewPermission();
                PostLoginService::setUserFactoryName();

                TNANotificationService::noticeBeforeNotification();
                PostLoginService::cacheClearForApprovedDataQuery();
                MachineDateNotificationService::machineServiceNotify();
                return redirect('dashboard');
            } else {
                session()->flash('error', 'These credentials do not match our records.');

                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return redirect()->back()->withInput()->withErrorMessage($e->getMessage());
        }
    }

    private function getInactiveUrls($erp_menu_view_status)
    {
        $inactiveUrls = [];
        if (Schema::hasTable('application_menu_active_data') && $erp_menu_view_status == 5) {
            $applicationMenuActiveData = DB::table('application_menu_active_data')->first();
            $inactiveUrls = $applicationMenuActiveData && $applicationMenuActiveData->inactive_urls ? json_decode($applicationMenuActiveData->inactive_urls, true) : [];
        }
        return $inactiveUrls;
    }

    private function getAdminMenus()
    {
        return (new AuthenticatedMenuViewStatus($this->getProductionEntryData()))->setMenuViewStatus();
    }

    private function getProductionEntryData()
    {
        $production_entry_type = 1;
        $style_filter_option = 1;
        $bundle_card_serial = 'bundle_no';
        $bundle_straight_serial_max_limit = 10000;
        $bundle_card_suffix_style = 0;
        $bundle_card_print_style = 0;
        $pdf_upload_menu_hide_status = 0;
        $cutting_plan_menu_hide_status = 0;
        $sewing_plan_menu_hide_status = 0;
        $erp_menu_view_status = 1;
        $cutting_target_version = 1;
        $sewing_line_target_vesrion = 1;
        $bundle_card_sticker_ratio_view_status = 0;
        $scan_data_caching_time = 86400;
        $cutting_qty_validation = 0;
        $fabric_cons_approval = 0;
        $max_bundle_qty = 0;
        $finishing_report = 'hourly_finishing_production';
        $line_wise_hour_show = GarmentsProductionEntry::DEFAULT_LINE_WISE_HOURS;
        if (Schema::hasTable('garments_production_entries')) {
            $garments_production_variable = DB::table('garments_production_entries')->where('factory_id', factoryId())->first();
            $production_entry_type = $garments_production_variable && collect($garments_production_variable)->has('entry_type') ? $garments_production_variable->entry_type : 1;
            $style_filter_option = $garments_production_variable && collect($garments_production_variable)->has('style_filter_option') ? $garments_production_variable->style_filter_option : 1;
            $bundle_card_serial = $garments_production_variable && collect($garments_production_variable)->has('bundle_card_serial') ? $garments_production_variable->bundle_card_serial : 'bundle_no';
            $bundle_card_suffix_style = $garments_production_variable && collect($garments_production_variable)->has('bundle_card_suffix_style') ? $garments_production_variable->bundle_card_suffix_style : 0;
            $bundle_straight_serial_max_limit = $garments_production_variable && collect($garments_production_variable)->has('bundle_straight_serial_max_limit') ? $garments_production_variable->bundle_straight_serial_max_limit : 10000;
            $bundle_card_print_style = $garments_production_variable && collect($garments_production_variable)->has('bundle_card_print_style') ? $garments_production_variable->bundle_card_print_style : 0;
            $pdf_upload_menu_hide_status = $garments_production_variable && collect($garments_production_variable)->has('pdf_upload_menu_hide_status') ? $garments_production_variable->pdf_upload_menu_hide_status : 0;
            $cutting_plan_menu_hide_status = $garments_production_variable && collect($garments_production_variable)->has('cutting_plan_menu_hide_status') ? $garments_production_variable->cutting_plan_menu_hide_status : 0;
            $sewing_plan_menu_hide_status = $garments_production_variable && collect($garments_production_variable)->has('sewing_plan_menu_hide_status') ? $garments_production_variable->sewing_plan_menu_hide_status : 0;
            $scan_data_caching_time = $garments_production_variable && collect($garments_production_variable)->has('scan_data_caching_time') ? $garments_production_variable->scan_data_caching_time : 86400;
            $erp_menu_view_status = $garments_production_variable && collect($garments_production_variable)->has('erp_menu_view_status') ? $garments_production_variable->erp_menu_view_status : 1;
            $cutting_target_version = $garments_production_variable && collect($garments_production_variable)->has('cutting_target_version') ? $garments_production_variable->cutting_target_version : 1;
            $sewing_line_target_vesrion = $garments_production_variable && collect($garments_production_variable)->has('sewing_line_target_vesrion') ? $garments_production_variable->sewing_line_target_vesrion : 1;
            $bundle_card_sticker_ratio_view_status = $garments_production_variable && collect($garments_production_variable)->has('bundle_card_sticker_ratio_view_status') ? $garments_production_variable->bundle_card_sticker_ratio_view_status : 0;
            $line_wise_hour_show = $garments_production_variable && collect($garments_production_variable)->has('line_wise_hour_show') ? json_decode($garments_production_variable->line_wise_hour_show, true) : $line_wise_hour_show;
            $cutting_qty_validation = $garments_production_variable && collect($garments_production_variable)->has('cutting_qty_validation') ? json_decode($garments_production_variable->cutting_qty_validation, true) : $cutting_qty_validation;
            $fabric_cons_approval = $garments_production_variable && collect($garments_production_variable)->has('fabric_cons_approval') ? json_decode($garments_production_variable->fabric_cons_approval, true) : $fabric_cons_approval;
            $finishing_report = $garments_production_variable && collect($garments_production_variable)->has('finishing_report') ? json_decode($garments_production_variable->finishing_report, true) : $finishing_report;
        }
        return [
            'production_entry_type' => $production_entry_type,
            'style_filter_option' => $style_filter_option,
            'bundle_card_serial' => $bundle_card_serial,
            'bundle_straight_serial_max_limit' => $bundle_straight_serial_max_limit,
            'bundle_card_suffix_style' => $bundle_card_suffix_style,
            'bundle_card_print_style' => $bundle_card_print_style,
            'pdf_upload_menu_hide_status' => $pdf_upload_menu_hide_status,
            'cutting_plan_menu_hide_status' => $cutting_plan_menu_hide_status,
            'sewing_plan_menu_hide_status' => $sewing_plan_menu_hide_status,
            'scan_data_caching_time' => $scan_data_caching_time,
            'erp_menu_view_status' => $erp_menu_view_status,
            'cutting_target_version' => $cutting_target_version,
            'sewing_line_target_vesrion' => $sewing_line_target_vesrion,
            'line_wise_hour_show' => $line_wise_hour_show,
            'bundle_card_sticker_ratio_view_status' => $bundle_card_sticker_ratio_view_status,
            'cutting_qty_validation' => $cutting_qty_validation,
            'fabric_cons_approval' => $fabric_cons_approval,
            'max_bundle_qty' => $max_bundle_qty,
            'finishing_report' => $finishing_report,
        ];
    }

    private function updateTodaysData(): bool
    {
        TotalProductionReport::whereDate('updated_at', '!=', Carbon::now()->toDateString())
            ->update([
                'todays_cutting' => 0,
                'todays_cutting_rejection' => 0,
                'todays_sent' => 0,
                'todays_received' => 0,
                'todays_print_rejection' => 0,
                'todays_embroidary_sent' => 0,
                'todays_embroidary_received' => 0,
                'todays_embroidary_rejection' => 0,
                'todays_input' => 0,
                'todays_sewing_output' => 0,
                'todays_sewing_rejection' => 0,
                'todays_washing_sent' => 0,
                'todays_washing_received' => 0,
                'todays_washing_rejection' => 0,
                'todays_received_for_poly' => 0,
                'todays_poly' => 0,
                'todays_poly_rejection' => 0,
                'todays_cartoon' => 0,
                'todays_pcs' => 0,
                'todays_shipment_qty' => 0,
            ]);

        return true;
    }

    public function logout()
    {
        if (Auth::check()) {
            User::find(Auth::user()->id)->update([
                'status' => false,
            ]);
        }
        Auth::logout();
        Session::flush();
        Cache::flush();

        return redirect('login');
    }
}
