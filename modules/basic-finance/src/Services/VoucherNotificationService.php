<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services;

use Exception;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\Models\Voucher;
use SkylarkSoft\GoRMG\BasicFinance\Models\Department;
use SkylarkSoft\GoRMG\BasicFinance\Notifications\VoucherChecked;
use SkylarkSoft\GoRMG\BasicFinance\Notifications\VoucherCreated;
use SkylarkSoft\GoRMG\SystemSettings\Models\BfVariableSetting;

class VoucherNotificationService
{
    //Voucher Created Notification to department head
    public static function voucherCreated($model)
    {
        $setting = BfVariableSetting::where('departmental_approval', 1)->first();
        if(isset($model->details->items) && !empty($setting))
        {
            $department_id = collect($model->details->items)->first()->department_id;
            $departmentInfo = Department::find($department_id);
            $notify_users = array_filter([$departmentInfo->notify_to, $departmentInfo->alternative_notify_to]);

            if(!empty($notify_users)){
                $users = User::whereIn('id', $notify_users)->get();
                Notification::sendNow($users, new VoucherCreated($model));
            }
        }
    }

    // Voucher Notification to Accounting head
    public static function voucherChecked($model)
    {
        $setting = BfVariableSetting::where('departmental_approval', 1)->first();
        if(isset($model->details->items) && !empty($setting))
        {
            $department_id = collect($model->details->items)->first()->department_id;
            $departmentInfo = Department::find($department_id);
            if(!empty($departmentInfo->notify_to) || !empty($departmentInfo->alternative_notify_to)){

                if($model->status_id == Voucher::CHECKED){
                    $department = Department::where('is_accounting', 1)->first();
                    if($department){
                        $notify_users = array_filter([$department->notify_to, $department->alternative_notify_to]);
                        if($notify_users)
                        {
                            $users = User::whereIn('id', $notify_users)->get();
                            Notification::sendNow($users, new VoucherChecked($model));
                        }
                    }
                }

            }
        }
    }
}
