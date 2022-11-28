<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services;

use SkylarkSoft\GoRMG\SystemSettings\Models\YarnStoreVariableSetting;

class YarnStoreApprovalMaintainService
{
    public static function getApprovalMaintainStatus()
    {
        return YarnStoreVariableSetting::query()
            ->where('factory_id', factoryId())
            ->first()->approval_maintain ?? 0;
    }
}
