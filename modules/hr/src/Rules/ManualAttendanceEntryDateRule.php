<?php

namespace SkylarkSoft\GoRMG\HR\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ManualAttendanceEntryDateRule implements Rule
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
        $date = Carbon::parse($value);
        $today = Carbon::now();
        return $date > $today ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Attendance date cannot be greater than today.';
    }
}
