<?php

namespace SkylarkSoft\GoRMG\HR\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\HR\Models\HrFastivalLeave;

class UniqueFestivalLeaveDateRule implements Rule
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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = strtoupper($value);
        $festival_leave = HrFastivalLeave::whereDate('leave_date', $value);
        if (request()->id) {
            $festival_leave = $festival_leave->where('id', '!=', request()->id);
        }
        $festival_leave = $festival_leave->first();
        return !$festival_leave;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This date already exists.';
    }
}
