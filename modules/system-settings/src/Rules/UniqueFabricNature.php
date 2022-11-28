<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;

class UniqueFabricNature implements Rule
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

        $fabric_nature = FabricNature::where([
            'name' => $value,
        ]);

        if (request()->route('id')) {
            $fabric_nature = $fabric_nature->where('id', '!=', request()->route('id'));
        }

        $fabric_nature = $fabric_nature->first();

        return $fabric_nature ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This fabric nature already exits.';
    }
}
