<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Compactor\Compactor;

class CompactorService
{

    public static function generateUniqueId(): string
    {
        $prefix = Compactor::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'C-' . date('y') . '-' . $generate;
    }

}
