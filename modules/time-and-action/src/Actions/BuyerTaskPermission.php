<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Actions;

use SkylarkSoft\GoRMG\TimeAndAction\Controllers\UserWiseTaskPermission;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNATask;
use SkylarkSoft\GoRMG\TimeAndAction\Models\UserTaskPermission;

class BuyerTaskPermission
{
    public function handle($buyerId, $tasks): bool
    {
        foreach ($tasks as $task) {
            $buyerTaskPermit = [
                'buyer_id' => $buyerId,
                'task_id' => $task,
                'plan_date_choice' => 0,
                'actual_date_choice' => 0,
                'created_by' => \Auth::id(),
            ];
            UserTaskPermission::query()->firstOrNew($buyerTaskPermit)->save();
        }
        return true;
    }
}
