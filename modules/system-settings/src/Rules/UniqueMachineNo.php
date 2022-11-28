<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Machine;

class UniqueMachineNo implements Rule
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

        $machine = Machine::where([
            'machine_no' => $value,
            'machine_type' => request()->get('machine_type'),
        ]);

        if (request()->route('id')) {
            $machine = $machine->where('id', '!=', request()->route('id'));
        }

        $machine = $machine->first();

        return $machine ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This machine no already exits.';
    }
}
