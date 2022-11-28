<?php

namespace SkylarkSoft\GoRMG\HR\Rules;

use Illuminate\Contracts\Validation\Rule;

class ManualAttendanceEntryRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     */
    public function __construct()
    {
        return auth()->check();
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
        $in_time = request()->get('in_time');
        $out_time = request()->get('out_time');
        $lunch_start = request()->get('lunch_start');
        $lunch_end = request()->get('lunch_end');

        return (!$in_time && !$out_time && !$lunch_start && !$lunch_end) ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'At least one punch time is required.';
    }
}
