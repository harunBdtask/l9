<?php

namespace SkylarkSoft\GoRMG\Knitting\Filters;

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

    public static function dateRangeFilter($key, $values = []): Closure
    {
        return function ($query) use ($key, $values) {
            $query->whereBetween($key, $values);
        };
    }

    public static function whereInFilter($key, $values = []): Closure
    {
        return function ($query) use ($key, $values) {
            $query->whereIn($key, $values);
        };
    }

    public static function whereLike($key, $values = []): Closure
    {
        return function ($query) use ($key, $values) {
            $query->where($key, 'LIKE', '%'. $values . '%');
        };
    }
}
