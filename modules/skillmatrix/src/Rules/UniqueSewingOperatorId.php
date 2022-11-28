<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Skillmatrix\Models\SewingOperator;

class UniqueSewingOperatorId implements Rule
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
        $sewingOperator = SewingOperator::where('name', strtoupper($value))
            ->where('factory_id', factoryId());

        if (request()->route('id')) {
            $sewingOperator = $sewingOperator->where('id', '!=', request()->route('id'));
        }

        $sewingOperator = $sewingOperator->first();

        return !$sewingOperator;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This sewing operator id already exists.';
    }
}
