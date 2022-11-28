<?php

namespace SkylarkSoft\GoRMG\Merchandising\Rules\Orders;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;

class CheckBuyerIdForBundleCards implements Rule
{

    public function passes($attribute, $value): bool
    {
        $bundleCard = BundleCard::query()
            ->where('order_id', request()->input('id'))
            ->first();

        if (isset($bundleCard) && $bundleCard->buyer_id != $value) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return "Buyer name of this order can't be changed because already bundle card created";
    }

}
