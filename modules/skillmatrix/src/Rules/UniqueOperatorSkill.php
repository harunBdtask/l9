<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueOperatorSkill implements Rule
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
        $machines = request()->get('sewing_machine_id');
        $processes = request()->get('sewing_process_id');

        foreach ($machines as $i => $machine) {
            foreach ($processes as $j => $process) {
                if (($i != $j) && ($machines[$i] == $machines[$j]) && ($processes[$i] == $processes[$j])) {
                    return false;
                }
            }
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
        return 'Duplicate process for machine.';
    }
}
