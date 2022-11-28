<?php

namespace SkylarkSoft\GoRMG\Merchandising\Directives;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\PageWiseViewPermission;
use SkylarkSoft\GoRMG\SystemSettings\Models\UserWiseBuyerPermission;

class BladeDirectiveCriteria
{
    public static function buyerPermission($buyerId, $permission): bool
    {
        $buyerPermission = in_array($buyerId, Session::get('buyerPermission'));

        if (in_array(getRole(), ['super-admin', 'admin']) || ($buyerPermission && session()->has($permission))) {
            return true;
        }
        return false;
    }

    public static function pageWiseViewPermission($viewName): bool
    {
        return in_array($viewName, Session::get('pageWiseViewPermission'));
    }

    public static function permission($permission): bool
    {
        if (in_array(getRole(), ['super-admin', 'admin']) || session()->has($permission)) {
            return true;
        }
        return false;
    }


    public static function buyerViewPermission($buyerId, $viewName): bool
    {
        $buyerPermission = in_array($buyerId, Session::get('viewBuyerPermission'));
        if ($buyerPermission && self::pageWiseViewPermission($viewName)) {
            return true;
        }
        return false;
    }

    public static function permittedBuyers(): Collection
    {
        return UserWiseBuyerPermission::query()
            ->where('user_id', Auth::id())
            ->where('permission_type', UserWiseBuyerPermission::BUYER_PERMISSION)
            ->pluck('buyer_id');
    }

    public static function permittedViewBuyers(): Collection
    {
        return UserWiseBuyerPermission::query()
            ->where('user_id', Auth::id())
            ->where('permission_type', UserWiseBuyerPermission::VIEW_BUYER_PERMISSION)
            ->pluck('view_buyer_id');
    }
}
