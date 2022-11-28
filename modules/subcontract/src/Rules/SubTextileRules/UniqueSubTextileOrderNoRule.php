<?php

namespace SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;

class UniqueSubTextileOrderNoRule implements Rule
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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = strtoupper($value);

        $order = SubTextileOrder::where([
            'order_no' => $value,
            'factory_id' => request()->get('factory_id'),
        ]);
        if (request()->route('subTextileOrder')) {
            $order = $order->where('id', '!=', request()->route('subTextileOrder')->id);
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
        return 'This order no already exits.';
    }
}
