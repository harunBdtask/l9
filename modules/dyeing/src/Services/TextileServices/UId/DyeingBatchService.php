<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;

class DyeingBatchService
{

    public static function generateUniqueId(): string
    {
        $prefix = DyeingBatch::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'DB-' . date('y') . '-' . $generate;
    }
}
