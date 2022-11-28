<?php

namespace SkylarkSoft\GoRMG\HR\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;

class UniquePunchCard implements Rule
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
        $uniqueId = HrEmployeeOfficialInfo::where('punch_card_id', $value);
        if (request()->employee_id) {
            $uniqueId = $uniqueId->where('employee_id', '!=', request()->employee_id);
        }
        $uniqueId = $uniqueId->first();
        return $uniqueId ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This Punch Card Is Already Exists!!';
    }
}
