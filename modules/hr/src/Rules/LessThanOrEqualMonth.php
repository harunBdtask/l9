<?php

namespace SkylarkSoft\GoRMG\HR\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class LessThanOrEqualMonth implements Rule
{
    private $secondAttr;

    /**
     * Create a new rule instance.
     *
     * @param $secondAttr
     */
    public function __construct($secondAttr)
    {
        $this->secondAttr = $secondAttr;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (! request()->has($this->secondAttr)) {
            return true;
        }

        $date = Carbon::parse(request($this->secondAttr));
        $diff = Carbon::parse($value)->diff($date)->m;
        Log::info('diff: ' . $diff);
        if ($diff >= 1) {
            return false;
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
        return 'Only 1 month is can be processed!';
    }
}
