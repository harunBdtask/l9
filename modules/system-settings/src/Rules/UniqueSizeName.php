<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class UniqueSizeName implements Rule
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
        $size = Size::where('name', $value)
            ->where('factory_id', \Auth::user()->factory_id);

        if (request()->route('id')) {
            $size = $size->where('id', '!=', request()->route('id'));
        }

        $size = $size->first();

        return $size ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This size name already exists.';
    }
}
