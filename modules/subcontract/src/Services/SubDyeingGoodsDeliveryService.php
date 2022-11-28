<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingGoodsDelivery;

class SubDyeingGoodsDeliveryService
{
    public static function generateUniqueId(): string
    {
        $prefix = SubDyeingGoodsDelivery::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'SDGD-' . date('y') . '-' . $generate;
    }
}
