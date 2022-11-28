<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services\MenuView;

use Illuminate\Support\Facades\Session;

class HrMenu implements MenuViewContract
{
    const HR_MENU_PRIORITY_START = 7500;
    const HR_MENU_PRIORITY_END = 7505;

    public function willRender(array $variables): array
    {
        $hrMenuViewStatus = $variables['hr_menu_view_status'] ?? 0;
        return collect(session('menu'))
            ->whereBetween('priority', [self::HR_MENU_PRIORITY_START, self::HR_MENU_PRIORITY_END])
            ->map(function ($collection) use ($hrMenuViewStatus) {
                $collection['view_status'] = $hrMenuViewStatus == 1;
                return $collection;
            })
            ->toArray();
    }
}
