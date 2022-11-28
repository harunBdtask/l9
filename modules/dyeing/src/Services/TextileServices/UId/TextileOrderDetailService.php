<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrderDetail;

class TextileOrderDetailService
{

    public static function generateUniqueId(): string
    {
        $prefix = TextileOrderDetail::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'TOD-' . date('y') . '-' . $generate;
    }
}
