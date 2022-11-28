<?php

namespace SkylarkSoft\GoRMG\McInventory\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\McInventory\Models\McMaintenance;
use SkylarkSoft\GoRMG\McInventory\Notifications\MachineServiceDateNotification;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class MachineDateNotificationService
{
    public static function machineServiceNotify()
    {
        $todayDate = Carbon::now()->format('Y-m-d');

        $userInfo = User::query()
            ->find(Auth::id());

        $machineService = McMaintenance::query()
            ->with('machine')
            ->whereDate('next_maintenance',$todayDate)
            ->get()
            ->each(function ($service) use ($userInfo) {
                $userInfo->notify(new MachineServiceDateNotification($service));
            });


    }
}
