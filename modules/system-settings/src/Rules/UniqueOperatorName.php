<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Operator;

class UniqueOperatorName implements Rule
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

        $operator = Operator::where([
            'operator_name' => $value,
            'operator_type' => request()->get('operator_type'),
        ]);

        if (request()->route('id')) {
            $operator = $operator->where('id', '!=', request()->route('id'));
        }

        $operator = $operator->first();

        return $operator ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This operator name already exits.';
    }
}
