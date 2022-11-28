<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services\MenuView;

use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsProductionEntry;

class SideBarService
{
    protected $menuViewContract;
    protected $menus;

    private function __construct(MenuViewContract $menuViewContract)
    {
        $this->menuViewContract = $menuViewContract;
    }

    public static function using(MenuViewContract $menuViewContract): SideBarService
    {
        return new static($menuViewContract);
    }

    private function modifiedMenus(): array
    {
        return $this->menuViewContract->willRender($this->variableSettings());
    }

    public function mergeModifiedMenus(): SideBarService
    {
        $this->menus = collect(session('menu'))->replace($this->modifiedMenus());
        return $this;
    }

    public function putSession()
    {
        Session::put('menu', $this->menus);
    }

    private function variableSettings(): array
    {
        return GarmentsProductionEntry::query()
            ->where('factory_id', factoryId())
            ->firstOr(function () {
                return collect([]);
            })
            ->toArray();
    }
}
