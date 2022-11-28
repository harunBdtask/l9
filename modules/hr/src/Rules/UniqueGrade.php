<?php

namespace SkylarkSoft\GoRMG\HR\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\HR\Models\HrGrade;

class UniqueGrade implements Rule
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
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = strtoupper($value);
        $grade = HrGrade::where('name', $value);
        if (request()->id) {
            $grade = $grade->where('id', '!=', request()->id);
        }
        $grade = $grade->first();
        return $grade ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This Grade Already Exists!!';
    }
}
