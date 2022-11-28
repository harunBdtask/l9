<?php

namespace SkylarkSoft\GoRMG\HR\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\HR\Models\HrDepartment;

class UniqueDepartment implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

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

        $department = HrDepartment::where('name', $value);

        if (request()->id) {
            $department = $department->where('id', '!=', request()->id);
        }
        $department = $department->first();
        return $department ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Department Already Exists!!';
    }
}
