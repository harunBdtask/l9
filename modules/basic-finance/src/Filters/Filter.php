<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Filters;

use Closure;

class Filter
{
    public static function applyFilter($key, $value): Closure
    {
        return function ($query) use ($key, $value) {
            $query->where($key, $value);
        };
    }

    public static function applyWhereInFilter($key, $array): Closure
    {
        return function ($query) use ($key, $array) {
            $query->whereIn($key, $array);
        };
    }

    public static function applyBetweenFilter($key, $array): Closure
    {
        return function ($query) use ($key, $array) {
            $query->whereBetween($key, $array);
        };
    }
}
