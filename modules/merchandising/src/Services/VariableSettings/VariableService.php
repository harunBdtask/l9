<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\VariableSettings;

use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;

class VariableService
{
    public static function getVariableSettings($factoryId, $buyerId)
    {
        return MerchandisingVariableSettings::query()
            ->where('factory_id', $factoryId)
            ->where('buyer_id', $buyerId)
            ->first();
    }
}
