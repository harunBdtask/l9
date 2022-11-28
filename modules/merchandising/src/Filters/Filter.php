<?php

namespace SkylarkSoft\GoRMG\Merchandising\Filters;

use Closure;

class Filter
{
    public static function applyFilter($key, $value): Closure
    {
        return function ($query) use ($key, $value) {
            $query->where($key, $value);
        };
    }

    public static function applyBetweenFilter($key, $array): Closure
    {
        return function ($query) use ($key, $array) {
            $query->whereBetween($key, $array);
        };
    }

    public static function applyDateFilter($key, $value): Closure
    {
        return function ($query) use ($key, $value) {
            $query->whereDate($key, $value);
        };
    }

    public static function applyMonthFilter($key, $value): Closure
    {
        return function ($query) use ($key, $value) {
            $query->whereMonth($key, $value);
        };
    }

    public static function applyYearFilter($key, $value): Closure
    {
        return function ($query) use ($key, $value) {
            $query->whereYear($key, $value);
        };
    }
}
