<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ManualProductionDateRule implements Rule
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
        $value = Carbon::parse(strtoupper($value));
        $today = Carbon::parse(today()->toDateString());
        
        return $value > $today ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Must be previous date or today!";
    }
}
