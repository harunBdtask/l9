<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Incoterm;

class UniqueIncoterm implements Rule
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

        $incoterm = Incoterm::where('incoterm', $value);
//            ->where('factory_id', \Auth::user()->factory_id);

        if (request()->route('id')) {
            $incoterm = $incoterm->where('id', '!=', request()->route('id'));
        }

        $incoterm = $incoterm->first();

        return $incoterm ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Incoterm has duplicate entry.';
    }
}
