<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Department;

class UniqueDepartment implements Rule
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

        $dept = Department::where('department_name', $value);

        if (request()->route('id')) {
            $dept = $dept->where('id', '!=', request()->route('id'));
        }

        $dept = $dept->first();

        return $dept ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This name for department already exits.';
    }
}
