<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricType;

class UniqueFabricType implements Rule
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

        $fabric_type = FabricType::where([
            'fabric_type_name' => $value,
        ]);

        if (request()->route('id')) {
            $fabric_type = $fabric_type->where('id', '!=', request()->route('id'));
        }

        $fabric_type = $fabric_type->first();

        return $fabric_type ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This fabric type already exits.';
    }
}
