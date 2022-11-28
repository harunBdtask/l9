<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Menu;
use SkylarkSoft\GoRMG\SystemSettings\Models\Module;
use SkylarkSoft\GoRMG\SystemSettings\Requests\MenuRequest;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q ?? '';
        $menus = Menu::withoutGlobalScope('factoryId')
            ->selectRaw('menus.*, modules.module_name as module_name')
            ->join('modules', 'menus.module_id', 'modules.id')
            ->when($q != '', function ($query) use ($q) {
                return $query->orWhere('menus.menu_name', 'like', '%' . $q . '%')
                    ->orWhere('menus.menu_url', 'like', '%' . $q . '%')
                    ->orWhere('modules.module_name', 'like', '%' . $q . '%');
            })
            ->orderBy('menus.id', 'DESC')
            ->paginate();

        return view('system-settings::pages.menus', ['menus' => $menus, 'q' => $q]);
    }

    public function create()
    {
        $modules = Module::withoutGlobalScope('factoryId')->pluck('module_name', 'id')->all();

        return view('system-settings::forms.menu', ['modules' => $modules, 'menu' => null]);
    }

    public function store(MenuRequest $request)
    {
        try {
            $menu = new Menu();
            $menu->menu_name = $request->get('menu_name');
            $menu->display_as = $request->get('display_as');
            $menu->menu_url = $request->get('menu_url');
            $menu->module_id = $request->get('module_id');
            $menu->submodule_id = $request->get('submodule_id');
            $menu->left_menu = $request->get('left_menu');
            $menu->sort = $request->get('sort');
            $menu->save();
            Session::flash('alert-success', 'Data stored successfully!!');

            return redirect('/menus');
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $menu = Menu::withoutGlobalScope('factoryId')->findOrFail($id);
        $modules = Module::withoutGlobalScope('factoryId')->pluck('module_name', 'id')->all();
        $submodules = Menu::withoutGlobalScope('factoryId')->where('module_id', $menu->module_id)->pluck('menu_name', 'id')->all();

        return view('system-settings::forms.menu', ['menu' => $menu, 'modules' => $modules, 'submodules' => $submodules]);
    }

    public function update($id, MenuRequest $request)
    {
        try {
            $menu = Menu::withoutGlobalScope('factoryId')->findOrFail($id);
            $menu->menu_name = $request->get('menu_name');
            $menu->display_as = $request->get('display_as');
            $menu->menu_url = $request->get('menu_url');
            $menu->module_id = $request->get('module_id');
            $menu->submodule_id = $request->get('submodule_id');
            $menu->left_menu = $request->get('left_menu');
            $menu->sort = $request->get('sort');
            $menu->save();
            Session::flash('alert-success', 'Data Updated successfully!!');

            return redirect('/menus');
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        if ($menu->assignPermissions()->count()) {
            Session::flash('alert-danger', "Cannot delete because some users are already assigned to this menu!!");

            return redirect()->back();
        }
        $menu->delete();

        return redirect('/menus');
    }

    public function getMenus($module_id)
    {
        return Menu::getMenus($module_id);
    }

    public function searchMenus(Request $request)
    {
        if (isset($request->q)) {
            $menus = Menu::withoutGlobalScope('factoryId')
                ->select('menus.*', 'modules.module_name')
                ->join('modules', 'menus.module_id', 'modules.id')
                ->where('menus.menu_name', 'like', '%' . $request->q . '%')
                ->orWhere('menus.menu_url', 'like', '%' . $request->q . '%')
                ->orWhere('modules.module_name', 'like', '%' . $request->q . '%')
                ->paginate();

            return view('system-settings::pages.search_menus', ['menus' => $menus, 'q' => $request->q]);
        } else {
            return redirect('menus');
        }
    }

    # menu seach by keyward
    public function getMenusByQuery(Request $request)
    {
        if (! $request->ajax() || ! isset($request->query_string)) {
            return response()->json(['success' => false, 'data' => ['menus' => []]]);
        }

        $menus = DB::table('menus')
            ->where('menu_name', 'like', '%' . $request->query_string . '%')
            ->where('left_menu', '=', 1)
            ->get();
        $menuSearchResult = '';
        foreach ($menus as $menu) {
            $menuSearchResult .= "<a href='{$menu->menu_url}' target='_blank'><li>{$menu->menu_name}</li></a>";
        }
        $menuSearchResultElement = ! empty($menuSearchResult) ? "<ul>{$menuSearchResult}</ul>" : '';

        return response()->json(['success' => true, 'data' => ['menuElement' => $menuSearchResultElement]]);
    }

    public function getSubmodules($module_id)
    {
        return Menu::where('module_id', $module_id)->pluck('menu_name', 'id')->all();
    }

    public function accessDenied($name = null)
    {
        return view('skeleton::access-denied');
    }

    public function searchJsonMenu(Request $request) {
        $menus = Menu::withoutGlobalScope('factoryId')
                        ->whereNotIn('menu_url', ['no-url', '#'])
                        ->where('left_menu', 1)
                        ->when($request->get('q') != '', function ($query) use ($request) {
                            return $query->where('menu_name', 'like', '%' . $request->get('q') . '%');
                        })
                        ->paginate();

        return response()->json([
            'status' => 'success',
            'data' => $menus
        ]);
    }
}
