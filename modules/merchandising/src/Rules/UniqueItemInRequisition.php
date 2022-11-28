<?php

namespace SkylarkSoft\GoRMG\Merchandising\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueItemInRequisition implements Rule
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
        $items = request()->get('item_id');

        if (count($items) != count(array_unique($items))) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Item has duplicate entry.';
    }
}
