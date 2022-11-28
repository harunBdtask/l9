<?php

use Illuminate\Support\Facades\Route;

use SkylarkSoft\GoRMG\McInventory\Controllers\DateWiseMachineMaintenanceController;
use SkylarkSoft\GoRMG\McInventory\Controllers\MachineTypeController;
use SkylarkSoft\GoRMG\McInventory\Controllers\MachineUnitController;
use SkylarkSoft\GoRMG\McInventory\Controllers\MaintenanceController;
use SkylarkSoft\GoRMG\McInventory\Controllers\MachineBrandController;
use SkylarkSoft\GoRMG\McInventory\Controllers\MachineProfileController;
use SkylarkSoft\GoRMG\McInventory\Controllers\MachineSubTypeController;
use SkylarkSoft\GoRMG\McInventory\Controllers\MachineLocationController;
use SkylarkSoft\GoRMG\McInventory\Controllers\MachineTransferController;
use SkylarkSoft\GoRMG\McInventory\Controllers\MachineDashboardController;
use SkylarkSoft\GoRMG\McInventory\Controllers\MaintenanceCalenderController;
use SkylarkSoft\GoRMG\McInventory\Controllers\InventoryChartFormatController;
use SkylarkSoft\GoRMG\McInventory\Controllers\MachineBarcodeGenerationController;

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'mc-inventory'], function () {

    Route::resource('machine-location', MachineLocationController::class, [
        'names' => [
            'index' => 'machine-location.index',
            'store' => 'machine-location.store',
            'update' => 'machine-location.update',
            'edit' => 'machine-location.edit',
            'destroy' => 'machine-location.destroy',
        ],
    ]);

    Route::resource('machine-type', MachineTypeController::class, [
        'names' => [
            'index' => 'machine-type.index',
            'store' => 'machine-type.store',
            'update' => 'machine-type.update',
            'edit' => 'machine-type.edit',
            'destroy' => 'machine-type.destroy',
        ],
    ]);

    Route::resource('machine-sub-type', MachineSubTypeController::class, [
        'names' => [
            'index' => 'machine-sub-type.index',
            'store' => 'machine-sub-type.store',
            'update' => 'machine-sub-type.update',
            'edit' => 'machine-sub-type.edit',
            'destroy' => 'machine-sub-type.destroy',
        ],
    ]);
    Route::get('machine-category-wise-type',[MachineSubTypeController::class,'getMachineType']);

    Route::resource('machine-brand', MachineBrandController::class, [
        'names' => [
            'index' => 'machine-brand.index',
            'store' => 'machine-brand.store',
            'update' => 'machine-brand.update',
            'edit' => 'machine-brand.edit',
            'destroy' => 'machine-brand.destroy',
        ],
    ]);

    Route::resource('machine-unit', MachineUnitController::class, [
        'names' => [
            'index' => 'machine-unit.index',
            'store' => 'machine-unit.store',
            'update' => 'machine-unit.update',
            'edit' => 'machine-unit.edit',
            'destroy' => 'machine-unit.destroy',
        ],
    ]);

    Route::group(['prefix' => '/machine-barcode-generation'], function () {
        Route::get('/', [MachineBarcodeGenerationController::class, 'index']);
        Route::post('/', [MachineBarcodeGenerationController::class, 'store']);
        Route::delete('/destroy/{id}', [MachineBarcodeGenerationController::class, 'destroy']);
        Route::get('/print', [MachineBarcodeGenerationController::class, 'print']);

        Route::get('/{any?}', [MachineBarcodeGenerationController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => '/machine-profile'], function () {
        Route::get('/', [MachineProfileController::class, 'index']);
        Route::post('/', [MachineProfileController::class, 'store']);

        Route::get('/edit/{id}', [MachineProfileController::class, 'create']);
        Route::delete('/delete/{id}', [MachineProfileController::class, 'destroy']);
        Route::get('/{any?}', [MachineProfileController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => '/maintenance-calender'], function () {
        Route::get('/', [MaintenanceCalenderController::class, 'index']);
        Route::get('/get-maintenance',[MaintenanceCalenderController::class,'getMaintenance']);
        Route::get('/get-pdf',[MaintenanceCalenderController::class,'pdf']);
        Route::get('/get-excel',[MaintenanceCalenderController::class,'excel']);
    });

    Route::group(['prefix' => '/maintenance'], function () {
        Route::get('/', [MaintenanceController::class, 'index']);
        Route::post('/', [MaintenanceController::class, 'store']);
        Route::delete('/{id}',[MaintenanceController::class,'destroy']);

        Route::get('/{any?}', [MaintenanceController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => '/machine-transfer'], function () {
        Route::get('/', [MachineTransferController::class, 'index']);
        Route::post('/', [MachineTransferController::class, 'store']);
        Route::get('/{id}/edit/',[MachineTransferController::class,'edit']);
        Route::put('/{id}', [MachineTransferController::class, 'update']);
        Route::delete('/{id}',[MachineTransferController::class,'destroy']);

        Route::get('/view/{id}',[MachineTransferController::class, 'view']);
        Route::get('/pdf/{id}',[MachineTransferController::class, 'pdf']);

        Route::get('/{any?}', [MachineTransferController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => '/machine-dashboard'], function () {
        Route::get('/', [MachineDashboardController::class, 'index']);
    });

    Route::group(['prefix' => '/inventory-chart-format'], function () {
        Route::get('/', [InventoryChartFormatController::class, 'index']);
        Route::get('/pdf',[InventoryChartFormatController::class,'machineChartFormatPDF']);
        Route::get('/excel',[InventoryChartFormatController::class,'machineChartFormatExcel']);
    });

    Route::get('/date-wise-machine-maintenance',[DateWiseMachineMaintenanceController::class, 'getData']);

    Route::group(['prefix' => '/api/', 'namespace' => 'SkylarkSoft\GoRMG\McInventory\Controllers'], function () {
        Route::post('/generate_barcode', 'MachineBarcodeGenerationController@store');
        Route::get('/fetchBarcodeGenerator/{McBarcodeGeneration}', 'MachineBarcodeGenerationController@fetchBarcodeGenerator');
        Route::get('/fetchAllInfo', 'MachineProfileController@fetchAllInfo');
        Route::get('/fetchMachineProfile/{barcode}', 'MachineProfileController@fetchMachineProfile');
        Route::post('/saveMachine', 'MachineProfileController@update');
        Route::post('/getNextMaintenance', 'MachineProfileController@getNextMaintenance');
        // Maintenance
        Route::post('/saveMaintenance', 'MaintenanceController@saveMaintenance');
        // Transfer
        Route::post('/saveTransfer', 'MachineTransferController@saveTransfer');
        Route::get('/fetchMachineTypes/{id}', 'MachineProfileController@fetchMachineTypes');
        Route::get('/fetchMachineSubTypes/{id}', 'MachineProfileController@fetchMachineSubTypes');
        Route::get('/get-machine-maintenace/{mcMaintenance}', [MaintenanceController::class, 'getData']);
    });

});

