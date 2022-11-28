<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Tumble\Tumble;

class TumbleService
{

    public static function generateUniqueId(): string
    {
        $prefix = Tumble::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'T-' . date('y') . '-' . $generate;
    }

}
