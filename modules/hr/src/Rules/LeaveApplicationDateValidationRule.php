<?php

namespace SkylarkSoft\GoRMG\HR\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\HR\Models\HrLeaveApplicationDetail;

class LeaveApplicationDateValidationRule implements Rule
{
    protected $leave_application_dates;

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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $employee_id = request()->get('employee_id');
        $leave_start = request()->get('leave_start');
        $leave_end = request()->get('leave_end');

        $leave_application_dates = HrLeaveApplicationDetail::where('employee_id', $employee_id)
            ->whereDate('leave_date', '>=', $leave_start)
            ->whereDate('leave_date', '<=', $leave_end)
            ->pluck('leave_date')->toArray();
        $this->leave_application_dates = $leave_application_dates;
        return (is_array($leave_application_dates) && count($leave_application_dates) > 0) ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This employee already taken leaves in ' . implode(', ', $this->leave_application_dates) . ' dates.';
    }
}
