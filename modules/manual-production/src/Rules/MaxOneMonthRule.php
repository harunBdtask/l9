<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class MaxOneMonthRule implements Rule
{

    private $message;

    public function passes($attribute, $value): bool
    {
        $fromDate = request('date_from');

        if (!$fromDate) {
            return true;
        }

        $dateDiff = Carbon::parse($fromDate)->diffInDays(Carbon::parse($value));

        $this->message = 'Max Date range is 31 days, You have select ' . ($dateDiff + 1) . ' days';

        return $dateDiff < 31;
    }

    public function message()
    {
        return $this->message;
    }
}
