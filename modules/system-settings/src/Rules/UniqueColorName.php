<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;

class UniqueColorName implements Rule
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
        $color = Color::where('name', $value);
//            ->where('factory_id', \Auth::user()->factory_id);

        if (request()->route('id')) {
            $color = $color->where('id', '!=', request()->route('id'));
        }

        $color = $color->first();

        return $color ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This color name already exists.';
    }
}
