<?php

namespace SkylarkSoft\GoRMG\HR\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\HR\Models\HrLeaveSetting;

class UniqueLeave implements Rule
{
    protected $id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
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
        $leave = HrLeaveSetting::where('name', $value);
        if ($this->id) {
            $leave = $leave->where('id', '!=', $this->id);
        }
        $leave = $leave->first();
        return !$leave;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This Leave Name Already Exists!!';
    }
}
