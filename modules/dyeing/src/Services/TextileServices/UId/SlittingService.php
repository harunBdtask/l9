<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Slitting\Slitting;

class SlittingService
{

    public static function generateUniqueId(): string
    {
        $prefix = Slitting::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'S-' . date('y') . '-' . $generate;
    }

}
