<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Services;

use SkylarkSoft\GoRMG\Finishingdroplets\Models\PnPackingList;

class PackingListUID
{
    public static function generateUniqueId(): string
    {
        $prefix = PnPackingList::query()->distinct('uid')->count('uid') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);
        return getPrefix() . 'PL-' . date('y') . '-' . $generate;
    }
}
