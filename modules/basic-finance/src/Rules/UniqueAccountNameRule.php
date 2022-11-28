<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;

class UniqueAccountNameRule implements Rule
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

        $account = Account::where('name', $value);

        if (request()->route('account')) {
            $account = $account->where('id', '!=', request()->route('account')->id);
        }

        return !$account->first();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This account already exists.';
    }
}
