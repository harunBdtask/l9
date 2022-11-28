<?php

namespace SkylarkSoft\GoRMG\Inventory\Filters;

use Closure;

class Filter
{
    public static function applyFilter($key, $value): Closure
    {
        return function ($query) use ($key, $value) {
            $query->where($key, $value);
        };
    }

    public static function yearFilter($key, $value): Closure
    {
        return function ($query) use ($key, $value) {
            $query->whereYear($key, $value);
        };
    }

    public static function dateFilter($key, $value): Closure
    {
        return function ($query) use ($key, $value) {
            $query->whereDate($key, $value);
        };
    }

    public static function betweenFilter($key, $array): Closure
    {
        return function ($query) use ($key, $array) {
            $query->whereBetween($key, $array);
        };
    }
}
