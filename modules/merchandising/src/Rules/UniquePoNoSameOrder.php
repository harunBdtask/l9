<?php

namespace SkylarkSoft\GoRMG\Merchandising\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class UniquePoNoSameOrder implements Rule
{
    public function passes($attribute, $value)
    {
        $po = PurchaseOrder::where('po_no', request()->get('po_no'))->whereHas('order', function ($q) {
            $q->where('id', request()->get('order_id'));
        })->first();

        if (! empty($po) && request()->get('id') != $po->id) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Purchase order already created with the buyer and order.';
    }
}
