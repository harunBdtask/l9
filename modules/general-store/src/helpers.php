<?php


use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\GeneralStore\Services\Calculations\AvgRateCalculator;
use SkylarkSoft\GoRMG\GeneralStore\Services\Calculations\OutRateCalculator;
use SkylarkSoft\GoRMG\SystemSettings\Models\Stores;

if (!function_exists('get_inv_stores')) {
    function get_inv_stores(): array
    {
        return Stores::all(['id', 'name'])->toArray();
    }
}

if (!function_exists('get_key_val_stores')) {
    function get_key_val_stores(): Collection
    {
        return collect(get_inv_stores())->pluck('name', 'id');
    }
}

if (!function_exists('get_store_name')) {
    function get_store_name($key)
    {
        return get_key_val_stores()[$key];
    }
}

if (!function_exists('rate_calc')) {
    function rate_calc($transactions)
    {
        return OutRateCalculator::calculate($transactions);
    }
}

if (!function_exists('avg_rate_calc')) {
    function avg_rate_calc($transactions)
    {
        return AvgRateCalculator::calculate($transactions);
    }
}
