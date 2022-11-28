<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Company;

class UniqueCompany implements Rule
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

        $company = Company::where('company_name', $value);

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
        return 'This name for Company already exits.';
    }
}
