<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services;

use SkylarkSoft\GoRMG\SystemSettings\Models\DyesChemicalStoreVariableSetting;

class DyesChemicalStoreApprovalMaintainService
{
    public static function getApprovalMaintainStatus()
    {
        return DyesChemicalStoreVariableSetting::query()
            ->where('factory_id', factoryId())
            ->first()->approval_maintain ?? 0;
    }
}
