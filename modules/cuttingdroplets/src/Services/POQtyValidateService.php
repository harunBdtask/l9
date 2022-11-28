<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Services;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class POQtyValidateService
{
    public function isMaxPOQtyExceeds(BundleCardGenerationDetail $bundleCardGenerationDetail): bool
    {
        if (!isCuttingQtyValidationEnabled()) {
            return false;
        }

        $isQtyExceed = false;
        $pos = collect($bundleCardGenerationDetail->po_details)->pluck('purchase_order_id');
        $colors = collect($bundleCardGenerationDetail->po_details)->pluck('color_id');
        $sizes = collect($bundleCardGenerationDetail->po_details)->pluck('size_id');

        $poQtyMatrix = PoColorSizeBreakdown::query()
            ->whereIn('purchase_order_id', $pos)
            ->where('garments_item_id', $bundleCardGenerationDetail->garments_item_id)
            ->pluck('quantity_matrix', 'purchase_order_id');

        $bundleCards = BundleCard::query()
            ->whereIn('color_id', $colors)
            ->whereIn('size_id', $sizes)
            ->whereIn('purchase_order_id', $pos)
            ->where('garments_item_id', $bundleCardGenerationDetail->garments_item_id)
            ->get();

        foreach ($bundleCardGenerationDetail->po_details as $po_detail) {
            $maxQty = collect($poQtyMatrix[$po_detail['purchase_order_id']])
                ->where('size_id', $po_detail['size_id'])
                ->where('color_id', $po_detail['color_id'])
                ->where('particular', PurchaseOrder::PLAN_CUT_QTY)
                ->first()['value'];

            $totalQty = $bundleCards
                ->where('purchase_order_id', $po_detail['purchase_order_id'])
                ->where('size_id', $po_detail['size_id'])
                ->where('color_id', $po_detail['color_id'])
                ->sum('quantity');

            if ($totalQty > $maxQty) {
                $isQtyExceed = true;
            }
        }

        return $isQtyExceed;
    }
}
