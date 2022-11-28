<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;

class TextileOrderService
{

    public static function generateUniqueId(): string
    {
        $prefix = TextileOrder::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'TO-' . date('y') . '-' . $generate;
    }
}
