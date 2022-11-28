<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;

class UniqueColorType implements Rule
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

    public function passes($attribute, $value)
    {
        $value = strtoupper($value);

        $color_type = ColorType::where('color_types', $value);

        if (request()->route('id')) {
            $color_type = $color_type->where('id', '!=', request()->route('id'));
        }
        $color_type = $color_type->first();

        return $color_type ? false : true;
    }

    public function message()
    {
        return 'This Entry Already Exists!!';
    }
}
