<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;

class PoColorSizeBreakdownReProcess
{
    public static function handle($orderId, $smvDetails)
    {
        $itemsToKeep = collect($smvDetails ?? [])
            ->pluck('item_id')
            ->flatten();
        PoColorSizeBreakdown::query()
            ->where('order_id', $orderId)
            ->whereNotIn('garments_item_id', $itemsToKeep)
            ->delete();
    }
}
