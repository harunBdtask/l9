<?php


namespace SkylarkSoft\GoRMG\Commercial\Rules;

use Illuminate\Contracts\Validation\Rule;

class CommercialRealizedValueRule implements Rule
{
    public function __construct()
    {
        return true;
    }

    public function passes($attribute, $value)
    {
        $key = explode('.', $attribute)[1];

        $dueRealizedValue = request('due_realized_value.' . $key);
        $invalid = true;

        if ($dueRealizedValue <= 0 && $value <= 0) {
            $invalid = false;
        }
        if ($value < 0) {
            $invalid = false;
        }

        return $invalid;
    }

    public function message()
    {
        return 'Positive number required';
    }
}
