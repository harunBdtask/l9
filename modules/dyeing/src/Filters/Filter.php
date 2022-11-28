<?php

namespace SkylarkSoft\GoRMG\Dyeing\Filters;

use Closure;

class Filter
{

    public static function applyFilter($key, $value): Closure
    {
        return function ($query) use ($key, $value) {
            $query->where($key, $value);
        };
    }

    public static function applyJsonContainsFilter($key, $value): Closure
    {
        return function ($query) use ($key, $value) {
            $query->whereJsonContains($key, $value);
        };
    }

    public static function applyBetweenFilter($key, $array): Closure
    {
        return function ($query) use ($key, $array) {
            $query->whereBetween($key, $array);
        };
    }

}
