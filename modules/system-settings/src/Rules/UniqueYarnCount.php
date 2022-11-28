<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;

class UniqueYarnCount implements Rule
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

        $yarn_count = YarnCount::where('yarn_count', $value);

        if (request()->route('id')) {
            $yarn_count = $yarn_count->where('id', '!=', request()->route('id'));
        }

        $yarn_count = $yarn_count->first();

        return $yarn_count ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This yarn count already exits.';
    }
}
