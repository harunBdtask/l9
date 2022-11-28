<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\KnitFabricFaultSetting;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

class UniqueKnitFabricFaultName implements Rule
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

        $fault = KnitFabricFaultSetting::query()->where([
            'name' => $value,
        ])->where('factory_id', factoryId());

        if (request()->route('id')) {
            $fault = $fault->where('id', '!=', request()->route('id'));
        }

        $fault = $fault->first();

        return !$fault;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This fault already exists.';
    }
}
