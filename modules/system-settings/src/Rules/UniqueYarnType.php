<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;

class UniqueYarnType implements Rule
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

        $yarn_type = YarnType::where([
            'yarn_type' => $value,
        ]);

        if (request()->route('id')) {
            $yarn_type = $yarn_type->where('id', '!=', request()->route('id'));
        }

        $yarn_type = $yarn_type->first();

        return $yarn_type ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This yarn type already exits.';
    }
}
