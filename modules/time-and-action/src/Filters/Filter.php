<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Filters;

use Closure;

class Filter
{
    public static function apply($key, $value): Closure
    {
        return function ($query) use ($key, $value) {
            $query->where($key, $value);
        };
    }
}
