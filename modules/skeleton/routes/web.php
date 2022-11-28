<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'web', 'namespace' => 'SkylarkSoft\GoRMG\Skeleton\Http\Controllers'], function () {
    Route::get('/', function () {
        return view('skeleton::login2');
    });
});