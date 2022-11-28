<?php

namespace SkylarkSoft\GoRMG\Commercial\Services\Commercial;

use Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\Role;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;
use SkylarkSoft\GoRMG\Commercial\Notifications\SalesContractNotification;

class NotificationService
{
    public static function notifySalesContract($salesContract, $user_id)
    {
        $user = User::query()->find($user_id);
        if ($user) {
                $user->notify(new SalesContractNotification($salesContract)
            );
        }
    }
    private static function sendNotificationToMD($salesContract)
    {
        $role = Role::where('slug', 'md')->first();
        if(isset($role->id)){
            $users = User::query()->where('role_id',$role->id)->get();
            if ($users) {
                foreach($users as $item){
                    self::notifySalesContract($salesContract, $item->id);
                }
            }
        }
        return true;
    }

    /**
     * @throws Throwable
     */
    public static function saleContractNotification($saleContractId)
    {
        $salesContract = SalesContract::find($saleContractId);
        $teamLeaders = Team::query()
        ->where('project_type','Commercial')
        ->where('role','Leader')
        ->get();

        try {
            if($teamLeaders){
                foreach($teamLeaders as $item){
                    self::notifySalesContract($salesContract, $item->member_id);
                }
            }

            //Send Notification to Managing Director
            $managingDirector = self::sendNotificationToMD($salesContract);

        } catch (\Throwable $e) {
            return $e->getMessage();
        }

    }

}