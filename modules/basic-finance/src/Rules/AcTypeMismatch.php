<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;

class AcTypeMismatch implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $acTypeId = request()->get('type_id');
        $parentAc = Account::find($value);

        if ($parentAc) {
            return $acTypeId == $parentAc->type_id;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Account type mismatch.';
    }
}
