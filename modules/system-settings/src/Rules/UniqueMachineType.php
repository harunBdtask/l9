<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\MachineType;

class UniqueMachineType implements Rule
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

        $machine_type = MachineType::where('name', $value)
            ->where('factory_id', Auth::user()->factory_id);

        if (request()->route('id')) {
            $machine_type = $machine_type->where('id', '!=', request()->route('id'));
        }

        $machine_type = $machine_type->first();

        return $machine_type ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This name for machine type already exits.';
    }
}
