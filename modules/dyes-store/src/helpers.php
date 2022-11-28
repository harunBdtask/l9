<?php

//["General Store"=>"General Store"]
use \SkylarkSoft\GoRMG\Inventory\Services\Store;
use \SkylarkSoft\GoRMG\Inventory\Services\Calculations\OutRateCalculator;
use \SkylarkSoft\GoRMG\Inventory\Services\Calculations\AvgRateCalculator;

if (!function_exists('get_inv_stores')) {
    function get_inv_stores(): array
    {
        return Store::getStores();
    }
}

if (!function_exists('get_key_val_stores')) {
    function get_key_val_stores(): \Illuminate\Support\Collection
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
