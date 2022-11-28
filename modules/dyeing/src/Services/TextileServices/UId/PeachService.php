<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Peach\Peach;

class PeachService
{

    public static function generateUniqueId(): string
    {
        $prefix = Peach::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'P-' . date('y') . '-' . $generate;
    }

}
