<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingFinishingProduction\DyeingFinishingProduction;

class DyeingFinishingProductionService
{

    public static function generateUniqueId(): string
    {
        $prefix = DyeingFinishingProduction::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'DP-' . date('y') . '-' . $generate;
    }
}
