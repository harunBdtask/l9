<?php

namespace SkylarkSoft\GoRMG\HR\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;
use SkylarkSoft\GoRMG\HR\Models\HrLeaveApplication;
use SkylarkSoft\GoRMG\HR\Models\HrLeaveSetting;

class Leave implements Rule
{

    /**
     * @var string
     */
    private $ruleMessage;

    public function __construct()
    {
        $this->ruleMessage = 'Leave is not available. You have run out of leaves.';
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
        if (request('is_approved') == 'no') {
            return true;
        }

        if (!request('employee_id')) {
            return false;
        }

        $employee = HrEmployee::with('officialInfo')->find(request('employee_id'));

        if (optional($employee->officialInfo)->type == HrEmployee::STAFF && Carbon::today()
                ->diffInMonths(optional($employee->officialInfo)->date_of_joining) < 6) {
            $this->ruleMessage = 'You are on probation! Leave is not available!';
            return false;
        }

        $totalUsed = HrLeaveApplication::where('employee_id', request('employee_id'))
            ->where('type', request('type'))
            ->whereYear('leave_date', date('Y'))
            ->where('is_approved', 'yes')
            ->pluck('duration')->sum();

        $allocatedLeave = HrLeaveSetting::find(request('type'))->number_of_days;
        $leaveDues = $allocatedLeave - $totalUsed;

        if (!$leaveDues) {
            $this->ruleMessage = "Leave available (0)";
            return false;
        }

        if (request('duration') > $leaveDues) {
            $this->ruleMessage = "You have applied for " . request('duration') . ' . Available leave ' . $leaveDues;
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please, Enter previous info';
    }
}
