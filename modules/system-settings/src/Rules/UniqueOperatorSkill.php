<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\OperatorSkill;

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
    public function passes($attribute, $value)
    {
        $value = strtoupper($value);

        $task = OperatorSkill::where('name', $value)
            ->where('factory_id', Auth::user()->factory_id);

        if (request()->route('id')) {
            $task = $task->where('id', '!=', request()->route('id'));
        }

        $task = $task->first();

        return $task ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This name for operator skill already exits.';
    }
}
