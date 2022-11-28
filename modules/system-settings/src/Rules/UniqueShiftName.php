<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

class UniqueShiftName implements Rule
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
        $value = strtoupper($value);

        $shift = Shift::where([
            'shift_name' => $value,
        ])->where('factory_id', factoryId());

        if (request()->route('id')) {
            $shift = $shift->where('id', '!=', request()->route('id'));
        }

        $shift = $shift->first();

        return $shift ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This shift already exits.';
    }
}
