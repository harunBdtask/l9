<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;

class UniqueBankRule implements Rule
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

        $bank = Account::query()->where('parent_ac', Account::BANK_ACCOUNT)->where('name', $value);

        if (request('account_id')) {
            $bank = $bank->where('id', '!=', request('account_id'));
        }

        $bank = $bank->first();

        return !$bank;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This bank already exists.';
    }
}
