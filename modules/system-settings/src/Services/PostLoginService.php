<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\PageWiseViewPermission;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Models\UserWiseBuyerPermission;

class PostLoginService
{
    public static function setBuyerPermission()
    {
        $buyerPermission = UserWiseBuyerPermission::query()
            ->where('user_id', Auth::id())
            ->where('permission_type', UserWiseBuyerPermission::BUYER_PERMISSION)
            ->pluck('buyer_id')->toArray();

        Session::put('buyerPermission', $buyerPermission);
    }

    public static function setViewBuyerPermission()
    {
        $viewBuyerPermission = UserWiseBuyerPermission::query()
            ->where('user_id', Auth::id())
            ->where('permission_type', UserWiseBuyerPermission::VIEW_BUYER_PERMISSION)
            ->pluck('view_buyer_id')->toArray();

        Session::put('viewBuyerPermission', $viewBuyerPermission);
    }

    public static function setPageWiseViewPermission()
    {
        $pageWiseViewPermission = PageWiseViewPermission::query()
            ->where('user_id', Auth::id())
            ->pluck('view_id')->toArray();

        Session::put('pageWiseViewPermission', $pageWiseViewPermission);
    }

    public static function setUserFactoryName()
    {
        $factoryName = User::with('factory')->findOrFail(Auth::id())->factory ?? '';
        Session::put('userFactoryName', $factoryName);
    }

    public static function cacheClearForApprovedDataQuery(): void
    {
        Cache::forget('orders_count');
        Cache::forget('budget_count');
        Cache::forget('po_count');
        Cache::forget('price_quotation_count');
        Cache::forget('fabric_booking_count');
        Cache::forget('short_fabric_booking_count');
        Cache::forget('trims_booking_count');
        Cache::forget('short_trims_booking_count');
    }
}
