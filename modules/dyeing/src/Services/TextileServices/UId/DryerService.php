<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Dryer\Dryer;

class DryerService
{

    public static function generateUniqueId(): string
    {
        $prefix = Dryer::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'D-' . date('y') . '-' . $generate;
    }

}
