<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class POQtyValidationRule implements Rule
{

    public function passes($attribute, $value): bool
    {
        if (!isCuttingQtyValidationEnabled()) {
            return true;
        }

        $idx = explode('.', $attribute)[1];
        $item = request('garments_item_id');
        $po = request('purchase_order_id')[$idx];
        $color = request('color')[$idx];
        $size = request('size')[$idx];

        $qtyMatrix = PoColorSizeBreakdown::query()
            ->where('purchase_order_id', $po)
            ->where('garments_item_id', $item)
            ->first()->quantity_matrix;

        $maxQty = collect($qtyMatrix)
            ->where('size_id', $size)
            ->where('color_id', $color)
            ->where('particular', PurchaseOrder::PLAN_CUT_QTY)
            ->first()['value'];

        $prevQty = BundleCard::query()->where(
            [
                'color_id' => $color,
                'size_id' => $size,
                'purchase_order_id' => $po,
                'garments_item_id' => $item,
            ])->groupBy(
            [
                'color_id',
                'size_id',
                'purchase_order_id',
                'garments_item_id',
            ])->sum('quantity');

        $totalQty = $prevQty + $value;
        if ($totalQty <= $maxQty) {
            return true;
        }
        return false;
    }

    public function message(): string
    {
        return 'PO qty. exceeds';
    }
}
