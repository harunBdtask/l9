<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingGoodsDelivery\DyeingGoodsDelivery;

class DyeingGoodsDeliveryService
{

    public static function generateUniqueId(): string
    {
        $prefix = DyeingGoodsDelivery::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'T-' . date('y') . '-' . $generate;
    }

}
