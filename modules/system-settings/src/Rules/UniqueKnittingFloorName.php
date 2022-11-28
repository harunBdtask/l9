<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\KnittingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Operator;

class UniqueKnittingFloorName implements Rule
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

        $operator = KnittingFloor::query()->where([
            'name' => $value
        ]);

        if (request()->route('id')) {
            $operator = $operator->where('id', '!=', request()->route('id'));
        }

        $operator = $operator->first();

        return !$operator;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This Knitting floor name already exits.';
    }
}
