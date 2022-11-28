<?php

namespace SkylarkSoft\GoRMG\HR\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\HR\Models\HrOtApprovalDetail;

class UniqueSectionOtApprovalRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     */
    public function __construct()
    {
        return auth()->check();
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
        $ot_approval = HrOtApprovalDetail::whereDate('ot_date', request()->get('ot_date'))
            ->where('section_id', $value);
        if (request()->id) {
            $ot_approval = $ot_approval->where('ot_approval_id', '!=', request()->id);
        }
        $ot_approval = $ot_approval->first();
        return $ot_approval ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ot approval found for this section in this date.';
    }
}
