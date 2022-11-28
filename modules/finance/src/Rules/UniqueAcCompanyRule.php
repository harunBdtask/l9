<?php

namespace SkylarkSoft\GoRMG\Finance\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Finance\Models\AcCompany;

class UniqueAcCompanyRule implements Rule
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

        $company = AcCompany::where('name', $value);

        if (request()->route('id')) {
            $company = $company->where('id', '!=', request()->route('id'));
        }

        $company = $company->first();

        return $company ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This company already exists.';
    }
}
