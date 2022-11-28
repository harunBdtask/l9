<?php

namespace SkylarkSoft\GoRMG\HR\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\HR\Models\HrOtApproval;

class UniqueOtApprovalDate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * UniqueOtApprovalDate constructor.
     */
    public function __construct()
    {
        return auth()->check();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = strtoupper($value);

        $ot_approval = HrOtApproval::whereDate('ot_date', $value)
            ->where('ot_for', request()->get('ot_for'))
            ->where('section_id', request()->get('section_id'))
            ->where('department_id', request()->get('department_id'));

        if (request()->route('id')) {
            $ot_approval = $ot_approval->where('id', '!=', request()->route('id'));
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
        return 'OT already added for this date and section.';
    }
}
