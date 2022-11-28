<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Rules\V3;

use Illuminate\Contracts\Validation\Rule;

class QtyRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return $value > 0;
    }

    public function message(): string
    {
        return "Qty Can't Be Zero";
    }
}
