<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\TQM\Controllers\CommonApiController;
use SkylarkSoft\GoRMG\TQM\Controllers\CuttingDhuController;
use SkylarkSoft\GoRMG\TQM\Controllers\CuttingDhuReportController;
use SkylarkSoft\GoRMG\TQM\Controllers\DhuReportController;
use SkylarkSoft\GoRMG\TQM\Controllers\FactoryDhuCumulativeReportController;
use SkylarkSoft\GoRMG\TQM\Controllers\FactoryDhuDailyReportController;
use SkylarkSoft\GoRMG\TQM\Controllers\FinishingDhuController;
use SkylarkSoft\GoRMG\TQM\Controllers\FinishingDhuReportController;
use SkylarkSoft\GoRMG\TQM\Controllers\SewingDhuController;
use SkylarkSoft\GoRMG\TQM\Controllers\SewingDhuReportController;
use SkylarkSoft\GoRMG\TQM\Controllers\TqmDefectController;
use SkylarkSoft\GoRMG\TQM\Controllers\TqmDhuLevelsController;

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'namespace' => 'SkylarkSoft\GoRMG\TQM\Controllers'], function () {

    Route::view('/cutting-dhu/{any?}', 'tqm::cutting-dhu.create')->where('any', '.*');
    Route::view('/sewing-dhu/{any?}', 'tqm::sewing-dhu.create')->where('any', '.*');
    Route::view('/finishing-dhu/{any?}', 'tqm::finishing-dhu.create')->where('any', '.*');

    // Defects related routes
    Route::group(['prefix' => 'tqm-defects'], function () {
        Route::get('/', [TqmDefectController::class, 'index']);
        Route::get('/create', [TqmDefectController::class, 'create']);
        Route::post('/', [TqmDefectController::class, 'store']);
        Route::get('/{defect}/edit', [TqmDefectController::class, 'edit']);
        Route::put('/{defect}', [TqmDefectController::class, 'update']);
        Route::delete('/{defect}', [TqmDefectController::class, 'destroy']);
        Route::post('/select-options', [TqmDefectController::class, 'fetchDefectsForSelect']);
    });

    Route::group(['prefix' => 'tqm-dhu-levels'], function () {
        Route::get('/', [TqmDhuLevelsController::class, 'index']);
        Route::get('/create', [TqmDhuLevelsController::class, 'create']);
        Route::post('/', [TqmDhuLevelsController::class, 'store']);
        Route::get('/{dhuLevel}/edit', [TqmDhuLevelsController::class, 'edit']);
        Route::put('/{dhuLevel}', [TqmDhuLevelsController::class, 'update']);
        Route::delete('/{dhuLevel}', [TqmDhuLevelsController::class, 'destroy']);
    });

    Route::group(['prefix' => 'dhu-report'], function () {
        Route::get('/', [DhuReportController::class, 'index']);
        Route::get('/get', [DhuReportController::class, 'getReport']);
        Route::get('/pdf', [DhuReportController::class, 'pdf']);
        Route::get('/excel', [DhuReportController::class, 'excel']);
    });

    Route::group(['prefix' => 'factory-dhu-cumulative-report'], function () {
        Route::get('/', [FactoryDhuCumulativeReportController::class, 'index']);
        Route::get('/get', [FactoryDhuCumulativeReportController::class, 'getReport']);
    });

    Route::group(['prefix' => 'factory-dhu-daily-report'], function () {
        Route::get('/', [FactoryDhuDailyReportController::class, 'index']);
        Route::get('/get', [FactoryDhuDailyReportController::class, 'getReport']);
    });

    Route::group(['prefix' => '/tqm/api/v1'], function () {
        // cutting dhu related apis
        Route::get('/cutting-floors', [CommonApiController::class, 'cuttingFloors']);
        Route::get('/fetch-cutting-defects', [CommonApiController::class, 'fetchCuttingDefects']);
        Route::post('/store-cutting-dhu', [CuttingDhuController::class, 'store']);
        Route::get('/bundle-cards-data', [CuttingDhuController::class, 'bundleCardsData']);

        // sewing dhu related apis
        Route::get('/sewing-floors', [CommonApiController::class, 'sewingFloors']);
        Route::get('/sewing-production-data', [SewingDhuController::class, 'sewingProductionData']);
        Route::post('/store-sewing-dhu', [SewingDhuController::class, 'store']);

        // finishing dhu related apis
        Route::get('/finishing-floors', [CommonApiController::class, 'finishingFloors']);
        Route::get('/finishing-production-data', [FinishingDhuController::class, 'finishingProductionData']);
        Route::post('/store-finishing-dhu', [FinishingDhuController::class, 'store']);
    });
});
