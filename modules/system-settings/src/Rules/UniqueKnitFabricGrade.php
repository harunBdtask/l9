<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\KnitFabricFaultSetting;
use SkylarkSoft\GoRMG\SystemSettings\Models\KnitFabricGradeSetting;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

class UniqueKnitFabricGrade implements Rule
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

        $grade = KnitFabricGradeSetting::query()->where([
            'grade' => $value,
        ])->where('factory_id', factoryId());

        if (request()->route('id')) {
            $grade = $grade->where('id', '!=', request()->route('id'));
        }

        $grade = $grade->first();

        return !$grade;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This Grade already exists.';
    }
}
