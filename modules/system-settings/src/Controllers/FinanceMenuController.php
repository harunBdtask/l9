<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinanceMenu;

class FinanceMenuController
{
    public function index()
    {
        $factories = Factory::query()->pluck('group_name', 'id');
        $menus = FinanceMenu::with('factory')->get();

        return view("system-settings::pages.finance-menu", compact('factories', 'menus'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['factory_id' => 'required', 'menu' => 'required']);

        try {
            $menu = FinanceMenu::query()
                ->where('factory_id', $request->factory_id)
                ->firstOrNew();
            $menu->factory_id = $request->factory_id;
            $menu->menu = $request->menu;
            $menu->save();

            session()->flash('success', S_SAVE_MSG);
            return redirect()->back();

        } catch (\Exception $e) {
            session()->flash('danger', E_SAVE_MSG);
            return redirect()->to('finance-menu');
        }
    }

    public function edit(FinanceMenu $menu)
    {
        $factories = Factory::query()->pluck('group_name', 'id');
        $menus = FinanceMenu::with('factory')->get();

        return view("system-settings::pages.finance-menu", compact('factories', 'menus', 'menu'));
    }
}