<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingHoliday;

class UniqueSewingHolidayRule implements Rule
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

        $sewing_holiday = SewingHoliday::where('holiday', $value);

        if (request()->route('id')) {
            $sewing_holiday = $sewing_holiday->where('id', '!=', request()->route('id'));
        }

        $sewing_holiday = $sewing_holiday->first();

        return $sewing_holiday ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This holiday already exists.';
    }
}
