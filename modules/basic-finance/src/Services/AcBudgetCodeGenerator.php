<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services;

use Carbon\Carbon;

class AcBudgetCodeGenerator
{
    public static function currentMonthCode($request): string
    {
        return Carbon::make($request->get('month'))->format('M-Y');
    }

    public static function previousMonthCode($month): string
    {
        return Carbon::make($month)->subMonth()->format('M-Y');
    }
}
