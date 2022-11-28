<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;

class SubTextileBatchService
{
    public static function generateUniqueId(): string
    {
        $prefix = SubDyeingBatch::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'SDB-' . date('y') . '-' . $generate;
    }
}
