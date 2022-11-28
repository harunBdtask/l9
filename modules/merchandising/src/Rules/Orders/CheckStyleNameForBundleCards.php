<?php

namespace SkylarkSoft\GoRMG\Merchandising\Rules\Orders;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class CheckStyleNameForBundleCards implements Rule
{

    public function passes($attribute, $value): bool
    {
        $bundleCard = BundleCard::query()
            ->where('order_id', request()->input('id'))
            ->first();

        if (isset($bundleCard)) {
            $styleName = Order::query()->findOrFail(request()->input('id'))['style_name'];

            return $styleName == $value;
        }

        return true;
    }

    public function message(): string
    {
        return "Style name of this order can't be changed because already bundle card created";
    }

}
