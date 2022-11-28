<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class UniqueBuyerName implements Rule
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

        $buyer = Buyer::where('name', $value)
            ->where('factory_id', \Auth::user()->factory_id);

        if (request()->route('id')) {
            $buyer = $buyer->where('id', '!=', request()->route('id'));
        }

        $buyer = $buyer->first();

        return $buyer ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This buyer name already exists.';
    }
}
