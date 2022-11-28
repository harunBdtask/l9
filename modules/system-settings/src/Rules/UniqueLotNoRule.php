<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;

class UniqueLotNoRule implements Rule
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
        $orderId = request()->get('order_id');
        $colorId = request()->get('color_id');
        $lot = Lot::query()
            ->where([
                'lot_no' => $value,
                'order_id' => $orderId,
                'color_id' => $colorId,
            ]);

        if (request()->route('id')) {
            $lot = $lot->where('id', '!=', request()->route('id'));
        }

        $lot = $lot->first();

        return $lot ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Lot no already exists for given Style and Color.';
    }
}
