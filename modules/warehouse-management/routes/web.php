<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\WarehouseManagement\Controllers\WarehouseCartonAllocationController;
use SkylarkSoft\GoRMG\WarehouseManagement\Controllers\WarehouseCartonController;
use SkylarkSoft\GoRMG\WarehouseManagement\Controllers\WarehouseFloorController;
use SkylarkSoft\GoRMG\WarehouseManagement\Controllers\WarehouseRackController;
use SkylarkSoft\GoRMG\WarehouseManagement\Controllers\WarehouseReportController;
use SkylarkSoft\GoRMG\WarehouseManagement\Controllers\WarehouseShipmentController;

Route::middleware(['web', 'auth', 'menu-auth'])->group(function () {
    Route::prefix('warehouse-floors')->group(function () {
        Route::get('', [WarehouseFloorController::class, 'index']);
        Route::get('/create', [WarehouseFloorController::class, 'create']);
        Route::post('', [WarehouseFloorController::class, 'store']);
        Route::get('/{id}/edit', [WarehouseFloorController::class, 'edit']);
        Route::put('/{id}', [WarehouseFloorController::class, 'update']);
        Route::delete('/{id}', [WarehouseFloorController::class, 'destroy']);
        Route::get('/search', [WarehouseFloorController::class, 'search']);
    });

    // Routes for warehouse floors
    Route::prefix('warehouse-racks')->group(function () {
        Route::get('', [WarehouseRackController::class, 'index']);
        Route::get('/create', [WarehouseRackController::class, 'create']);
        Route::post('', [WarehouseRackController::class, 'store']);
        Route::get('/{id}/edit', [WarehouseRackController::class, 'edit']);
        Route::put('/{id}', [WarehouseRackController::class, 'update']);
        Route::delete('/{id}', [WarehouseRackController::class, 'destroy']);
        Route::get('/search', [WarehouseRackController::class, 'search']);
    });
    Route::get('/get-warehouse-racks/{floor_id}', [WarehouseRackController::class, 'getWarehouseRacks']);

    // Routes for warehouse floors
    Route::prefix('warehouse-cartons')->group(function () {
        Route::get('', [WarehouseCartonController::class, 'index']);
        Route::get('/create', [WarehouseCartonController::class, 'create']);
        Route::post('', [WarehouseCartonController::class, 'store']);
        Route::get('/{id}/edit', [WarehouseCartonController::class, 'edit']);
        Route::get('/{id}/show', [WarehouseCartonController::class, 'show']);
        Route::put('/{id}', [WarehouseCartonController::class, 'update']);
        Route::delete('/{id}', [WarehouseCartonController::class, 'destroy']);
        Route::get('/search', [WarehouseCartonController::class, 'search']);
    });
    Route::get('/get-purchase-order-details-for-warehouse/{purchase_order_id}', [WarehouseCartonController::class, 'getPurchaseOrderDetailsForWarehouse']);

    // Routes for carton allocation to rack
    Route::prefix('warehouse-carton-allocation')->group(function () {
        Route::get('/', [WarehouseCartonAllocationController::class, 'index']);
        Route::post('/', [WarehouseCartonAllocationController::class, 'storeCartonInRack']);
    });
    Route::get('/get-warehouse-rack-allocated-cartons/{warehouse_rack_id}', [WarehouseCartonAllocationController::class, 'getWarehouseRackAllocatedCartons']);

    // Routes for carton shipment scan
    Route::prefix('warehouse-shipment-scan')->group(function () {
        Route::get('/', [WarehouseShipmentController::class, 'index']);
        Route::post('/', [WarehouseShipmentController::class, 'shipmentScanPost']);
    });
    Route::post('/warehouse-shipment-challan-create', [WarehouseShipmentController::class, 'shipmentChallanCreate']);

    Route::prefix('warehouse-shipment-challans')->group(function () {
        Route::get('', [WarehouseShipmentController::class, 'shipmentChallanList']);
        Route::get('/search', [WarehouseShipmentController::class, 'shipmentChallanListSearch']);
        Route::get('/{challan_no}', [WarehouseShipmentController::class, 'shipmentChallanView']);
    });

    // Routes for Reports
    Route::match(['GET', 'POST'], '/warehouse-daily-in-report', [WarehouseReportController::class, 'dailyInReport']);
    Route::get('/warehouse-daily-in-report-download/{type}/{from_date}/{to_date}', [WarehouseReportController::class, 'dailyInReportDownload']);

    Route::match(['GET', 'POST'], '/warehouse-daily-out-report', [WarehouseReportController::class, 'dailyOutReport']);
    Route::get('/warehouse-daily-out-report-download/{type}/{from_date}/{to_date}', [WarehouseReportController::class, 'dailyOutReportDownload']);

    Route::match(['GET', 'POST'], '/warehouse-floor-wise-status-report', [WarehouseReportController::class, 'floorWiseStatusReport']);
    Route::get('/warehouse-floor-wise-status-report-download/{type}/{warehouse_floor_id}', [WarehouseReportController::class, 'floorWiseStatusReportDownload']);

    Route::get('/warehouse-buyer-style-wise-status-report', [WarehouseReportController::class, 'buyerStyleWiseStatusReport']);
    Route::get('/warehouse-buyer-style-wise-status-report-download/{type}/{purchase_order_id}/{color_id?}', [WarehouseReportController::class, 'buyerStyleWiseStatusReportDownload']);

    Route::get('/get-purchase-order-wise-warehouse-report/{purchase_order_id}', [WarehouseReportController::class, 'getPurchaseOrderWiseWarehouseReport']);
    Route::get('/get-color-wise-warehouse-report/{purchase_order_id}/{color_id}', [WarehouseReportController::class, 'getColorWiseWarehouseReport']);

    Route::match(['GET', 'POST'], '/warehouse-scan-barcode-check', [WarehouseReportController::class, 'scanBarcodeCheck']);
});
