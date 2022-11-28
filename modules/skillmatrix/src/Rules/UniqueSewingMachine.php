<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Skillmatrix\Models\SewingMachine;

class UniqueSewingMachine implements Rule
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
        $sewingMachine = SewingMachine::where('name', strtoupper($value))
            ->where('factory_id', factoryId());

        if (request()->route('id')) {
            $sewingMachine = $sewingMachine->where('id', '!=', request()->route('id'));
        }

        $sewingMachine = $sewingMachine->first();

        return !$sewingMachine;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This sewing machine already exists.';
    }
}
