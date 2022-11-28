<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;

class UniqueCuttingFloor implements Rule
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

        $cutting_floor = CuttingFloor::where('floor_no', $value)
            ->where('factory_id', \Auth::user()->factory_id);

        if (request()->route('id')) {
            $cutting_floor = $cutting_floor->where('id', '!=', request()->route('id'));
        }

        $cutting_floor = $cutting_floor->first();

        return $cutting_floor ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This floor no already exits.';
    }
}
