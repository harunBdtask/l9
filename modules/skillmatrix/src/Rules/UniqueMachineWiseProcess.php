<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueMachineWiseProcess implements Rule
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
        if (count(array_filter(request('sewing_process_id'))) != count(array_unique(array_filter(request('sewing_process_id'))))) {
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
        return 'Please remove duplicate entry.';
    }
}
