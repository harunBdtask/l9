<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Skillmatrix\Models\ProcessAssignToMachine;

class CheckMachineWiseUniqueProcess implements Rule
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
    public function passes($attribute, $value): bool
    {
        $checkoutAlreadyExist = ProcessAssignToMachine::query()
            ->where('sewing_machine_id', request('sewing_machine_id'))
            ->whereIn('sewing_process_id', request('sewing_process_id'))
            ->count();

        if ($checkoutAlreadyExist) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Assigned process already exist for this machine.';
    }
}
