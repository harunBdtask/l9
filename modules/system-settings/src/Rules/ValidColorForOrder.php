<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;

class ValidColorForOrder implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = strtoupper($value);

        $purchaseOrderId = request()->get('purchase_order_id');
        $colorId = request()->get('color_id');

        foreach ($purchaseOrderId as $purchaseOrder) {
            ${'po'.$purchaseOrder} = PurchaseOrderDetail::where('purchase_order_id', $purchaseOrder)
                ->where('color_id', $colorId)->count();
            if (! ${'po'.$purchaseOrder}) {
                return false;
            }
        }

        $purchaseOrderDetail = PurchaseOrderDetail::whereIn('purchase_order_id', $purchaseOrderId)
            ->where('color_id', $colorId);

        $purchaseOrderDetail = $purchaseOrderDetail->first();

        return $purchaseOrderDetail ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Color mismatch for the given purchase order.';
    }
}
