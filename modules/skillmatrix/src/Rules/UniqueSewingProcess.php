<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Skillmatrix\Models\SewingProcess;

class UniqueSewingProcess implements Rule
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
        $process = SewingProcess::where('name', strtoupper($value))
            ->where('factory_id', factoryId());

        if (request()->route('id')) {
            $process = $process->where('id', '!=', request()->route('id'));
        }

        $process = $process->first();

        return !$process;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This process is already exists.';
    }
}
