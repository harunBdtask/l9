<?php

namespace SkylarkSoft\GoRMG\Finance\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Finance\Models\Account;

class AcTypeMismatch implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
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
    public function message()
    {
        return 'Account type mismatch.';
    }
}