<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;

class UniqueCurrency implements Rule
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

        $currency = Currency::where('currency_name', $value);
//            ->where('factory_id', \Auth::user()->factory_id);

        if (request()->route('id')) {
            $currency = $currency->where('id', '!=', request()->route('id'));
        }

        $currency = $currency->first();

        return $currency ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Currency has duplicate entry.';
    }
}
