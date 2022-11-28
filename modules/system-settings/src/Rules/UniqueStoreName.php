<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Stores;

class UniqueStoreName implements Rule
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
        $stores = Stores::where('name', $value);
        if (request()->route('id')) {
            $stores = $stores->where('id', '!=', request()->route('id'));
        }

        $stores = $stores->first();

        return $stores ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This store name already exits.';
    }
}
