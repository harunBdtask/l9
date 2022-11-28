<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingHoliday;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingPlan;

class SewingHolidayOnExistingPlanRule implements Rule
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
        $sewing_plan = SewingPlan::whereDate('start_date', '<=', $value)
            ->whereDate('end_date', '>=', $value)
            ->count();

        return $sewing_plan ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Plan exist in this day.';
    }
}
