<?php

namespace SkylarkSoft\GoRMG\Merchandising\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class UniqueOrderStyle implements Rule
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
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = strtoupper($value);
        $order = Order::withoutGlobalScope('factoryId')->where('order_style_no', $value);
//            ->where('factory_id', \Auth::user()->factory_id);
        if (request()->order_id) {
            $order = $order->where('id', '!=', request()->order_id);
        }
        $order = $order->first();

        return $order ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Same Style Exists';
    }
}
