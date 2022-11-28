<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Order;

class AvgUnitPriceCalculator
{
    public static function calculateForPcs($purchaseOrder): float
    {
        $purchaseOrderQtySum = $purchaseOrder->sum('po_quantity');
        $purchaseOrderAmountSum = $purchaseOrder->selectRaw('SUM(po_quantity * avg_rate_pc_set) as amount_sum')->first();
        return $purchaseOrder->count() !== 0 && $purchaseOrderQtySum !== 0 ?
            format($purchaseOrderAmountSum->amount_sum / $purchaseOrderQtySum)
            : 0;
    }

    public static function calculateForSet($purchaseOrder): float
    {
        return $purchaseOrder->count() !== 0 ? format($purchaseOrder->sum('avg_rate_pc_set') / $purchaseOrder->count()) : 0;
    }
}
