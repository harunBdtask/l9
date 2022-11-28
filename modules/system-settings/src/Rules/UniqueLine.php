<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class UniqueLine implements Rule
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
        $floorId = request()->get('floor_id');
        $line = Line::where('line_no', $value)
            ->where('floor_id', $floorId)
            ->where('factory_id', Auth::user()->factory_id);

        if (request()->route('id')) {
            $line = $line->where('id', '!=', request()->route('id'));
        }

        $line = $line->first();

        return $line ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This line already exists.';
    }
}
