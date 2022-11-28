<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\BasicFinance\Models\Company;

class UniqueCompanyRule implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $value = strtoupper($value);

        $company = Company::where('name', $value);

        if (request()->route('id')) {
            $company = $company->where('id', '!=', request()->route('id'));
        }

        $company = $company->first();

        return !$company;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This company already exists.';
    }
}
