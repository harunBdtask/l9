<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;

class FabricConsApprovalRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!isFabricConsApprovalEnabled()) {
            return true;
        }

        $buyer = request('buyer_id');
        $order = request('order_id');
        $item = request('garments_item_id');
        $colors = request('color');

        if (!request('color')) {
            $colors = Lot::query()
                ->findMany(request('lot'))
                ->pluck('color_id')
                ->toArray();
        }

        $poNos = request('purchase_order_id');
        $sid = [];

        foreach ($colors as $key => $color) {
            $bundleSid =  BundleCard::query()
                ->where('buyer_id', $buyer)
                ->where('order_id', $order)
                ->where('garments_item_id', $item)
                ->where('color_id', $color)
                ->where('purchase_order_id', $poNos[$key])
                ->distinct('bundle_card_generation_detail_id')
                ->orderByDesc('bundle_card_generation_detail_id')
                ->firstOr(function () {
                    return (object)[
                        'bundle_card_generation_detail_id' => null
                    ];
                })->bundle_card_generation_detail_id;
            if ($bundleSid) {
                $sid[] = $bundleSid;
            }
        }

        $bundleCardCount = BundleCardGenerationDetail::query()
            ->whereIn('id', collect($sid)->unique())
            ->where('cons_result', '0')
            ->where('is_cons_approved', '0')
            ->count();
        return !($bundleCardCount > 0);
    }

    public function message(): string
    {
        return 'Previous Cutting consumption failed.';
    }
}
