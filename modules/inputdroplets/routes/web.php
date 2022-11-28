<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Inputdroplets\Controllers\ArchivedCuttingInventoryController;
use SkylarkSoft\GoRMG\Inputdroplets\Controllers\CuttingInventoryController;
use SkylarkSoft\GoRMG\Inputdroplets\Controllers\CuttingRejectionController;
use SkylarkSoft\GoRMG\Inputdroplets\Controllers\DailySizeWiseInputReportController;
use SkylarkSoft\GoRMG\Inputdroplets\Controllers\InputClosingAndLineController;
use SkylarkSoft\GoRMG\Inputdroplets\Controllers\InputDashboardV2Controller;
use SkylarkSoft\GoRMG\Inputdroplets\Controllers\InventoryController;
use SkylarkSoft\GoRMG\Inputdroplets\Controllers\LineSizeWiseInputReportController;
use SkylarkSoft\GoRMG\Inputdroplets\Controllers\LineWiseOverallReportController;
use SkylarkSoft\GoRMG\Inputdroplets\Controllers\OrderColorSizeController;
use SkylarkSoft\GoRMG\Inputdroplets\Controllers\SewingInputDashboardController;
use SkylarkSoft\GoRMG\Inputdroplets\Controllers\V2\DateWiseReportController;

Route::middleware(['web', 'auth', 'menu-auth'])->group(function () {

    Route::get('/cutting-inventory-scan', [CuttingInventoryController::class, 'cuttingInventoryScan']);
    Route::post('cutting-inventory-scan-post', [CuttingInventoryController::class, 'cuttingInventoryScanPost']);
    Route::get('/cutting-rejection', [CuttingRejectionController::class, 'cuttingRejectionForm']);
    Route::post('/cutting-rejection-post', [CuttingRejectionController::class, 'cuttingRejectionPost']);

    Route::get('/create-challan-tag/{challan_no}', [CuttingInventoryController::class, 'createChallanTag']);
    Route::get('/create-challan/{challan_no}', [CuttingInventoryController::class, 'createChallan']);
    Route::post('create-challan-sewing-input', [CuttingInventoryController::class, 'createChallanForLineInput']);
    Route::get('/create-challan-for-sewing/{id}', [CuttingInventoryController::class, 'createChallanForSewing']);
    Route::post('/create-challan-for-line', [CuttingInventoryController::class, 'createChallanForSewingLine']);

    Route::get('/view-challan-list', [CuttingInventoryController::class, 'viewChallanList']);
    Route::get('/view-challan/{id}', [CuttingInventoryController::class, 'viewChallan']);
    Route::get('/edit-challan/{id}', [CuttingInventoryController::class, 'editChallan']);
    Route::post('/update-challan-line', [CuttingInventoryController::class, 'updateChallan']);
    Route::get('/view-tag-list', [CuttingInventoryController::class, 'viewTagList']);
    Route::get('/view-tag/{challan_no}', [CuttingInventoryController::class, 'viewTag']);
    Route::get('/search-challan-or-tag', [CuttingInventoryController::class, 'searchChallanOrTag']);
    Route::delete('/delete-challan/{id}', [CuttingInventoryController::class, 'destroy']);
    Route::get('/add-bundle-to-tag', [CuttingInventoryController::class, 'addBundleToTag']);
    Route::post('/add-bundle-tag-post', [CuttingInventoryController::class, 'addBundleToTagPost']);
    Route::get('/view-challan/bin-card/{id}', [CuttingInventoryController::class, 'viewBinCard']);
    Route::patch("/update-challan-rib-details/{id}", [CuttingInventoryController::class, 'updateChallanRibDetails']);
    Route::get("/view-challan-rib-details/{id}", [CuttingInventoryController::class, 'viewChallanRibDetails']);

    Route::get('/view-archived-challan-list', [ArchivedCuttingInventoryController::class, 'viewChallanList']);
    Route::get('/search-archived-challan-or-tag', [ArchivedCuttingInventoryController::class, 'searchChallanOrTag']);
    Route::get('/view-archived-challan-wise-input-bundles/{challan_no}', [ArchivedCuttingInventoryController::class, 'viewInputChallanWiseBundlesList']);
    Route::get('/view-archived-challan/{id}', [ArchivedCuttingInventoryController::class, 'viewChallan']);

    Route::get('/challan-wise-bundles', [CuttingInventoryController::class, 'challanWiseBundles']);
    Route::get('/challan-wise-bundles-list/{challan_no}', [CuttingInventoryController::class, 'challanWiseBundlesList']);
    Route::get('/view-challan-wise-input-bundles/{challan_no}', [CuttingInventoryController::class, 'viewInputChallanWiseBundlesList']);
    Route::get('/view-challan-wise-deleted-input-bundles/{challan_no}', [CuttingInventoryController::class, 'viewInputChallanWiseDeletedBundlesList']);

    Route::get('/bundle-received-from-print', [CuttingInventoryController::class, 'bundleReceivedRromPrint']);
    Route::post('/bundle-received-from-print-post', [CuttingInventoryController::class, 'bundleReceivedFromPrintPost']);

    Route::get('/view-tag-wise-bundles/{challan_no}', [CuttingInventoryController::class, 'viewTagWiseBundles']);
    Route::delete('/delete-input-bundle/{bundle_card_id}', [CuttingInventoryController::class, 'deleteInputBundle']);

    // Report routes
    Route::get('/order-wise-cutting-inventory-summary', [InventoryController::class, 'getOrderWiseCuttingInventorySummary']);
    Route::get('/all-orders-inventory-report-download', [InventoryController::class, 'orderWiseCuttingInventorySummaryDownload']);

    Route::get('/cutting-no-wise-inventory-challan', [InventoryController::class, 'getCuttingNoWiseInventoryChallanCount']);
    Route::match(['GET', 'POST'], '/cutting-no-wise-inventory-challan-post', [InventoryController::class, 'getCuttingNoWiseInventoryChallanCountPost']);
    Route::get('/cutting-no-wise-inventory-challan-report-download/{type}/{purchase_order_id}/{color_id}/{cutting_no}', [InventoryController::class, 'getCuttingNoWiseInventoryChallanReportDownload']);
    Route::get('/inventory-challan-count-report-download/{type}/{purchase_order_id}', [InventoryController::class, 'getInventoryChallanCountReportDownload']);

    Route::get('/inventory-challan-count', [InventoryController::class, 'getInventoryChallanCount']);
    Route::get('/inventory-challan-count-post/{purchase_order_id}', [InventoryController::class, 'getInventoryChallanCountPost']);

    Route::get('buyer-sewing-line-input', [OrderColorSizeController::class, 'buyerWiseInputReport']);
    Route::get('get-buyer-wise-sewing-line-input', [OrderColorSizeController::class, 'getBuyerWiseInputReportData']);
    Route::get('/buyer-wise-sewing-line-input-report-download/{type}/{buyer_id}/{page}', [OrderColorSizeController::class, 'buyerWiseInputReportDownload']);

    Route::get('order-sewing-line-input', [OrderColorSizeController::class, 'orderWiseInputReport']);
    Route::get('order-sewing-line-input-download', [OrderColorSizeController::class, 'orderWiseInputReportDownload']);

    Route::get('booking-no-po-and-color-report', [OrderColorSizeController::class, 'bookingNoPoAndColorReport']);
    Route::get('/get-style-wise-input/{order_id}', [OrderColorSizeController::class, 'getStyleWiseInputReport']);
    Route::get('/get-style-wise-sewing-input-report-download/{type}/{style_id}', [OrderColorSizeController::class, 'getStyleInputReportDownload']);
    Route::get('/get-order-color-size-wise-sewing-input/{order_id}/{po_id}/{garments_item_id}', [OrderColorSizeController::class, 'getOrderColorWiseInputReport']);
    Route::get('/get-order-color-size-wise-sewing-input-report-download/{type}/{order_id}/{po_id}/{garments_item_id}', [OrderColorSizeController::class, 'getOrderColorWiseInputReportDownload']);
    Route::get('/get-size-wise-sewing-input/{order_id}/{po_id}/{color_id}/{garments_item_id}', [OrderColorSizeController::class, 'getSizeWiseInputReport']);
    Route::get('/get-size-wise-sewing-input-download/{type}/{order_id}/{po_id}/{color_id}/{garments_item_id}', [OrderColorSizeController::class, 'getSizeWiseInputReportDownload']);

    Route::match(['GET', 'POST'], '/date-wise-sewing-input', [OrderColorSizeController::class, 'getDateWiseInput']);
    Route::get('/date-wise-sewing-input-download/{type}/{date}', [OrderColorSizeController::class, 'getDateWiseInputDownload']);
    Route::match(['GET', 'POST'], '/daily-input-status', [OrderColorSizeController::class, 'getDailyInputStatusReport']);
    Route::get('/daily-input-status-download/{type}/{date}', [OrderColorSizeController::class, 'getDailyInputStatusDownload']);
    Route::match(['GET', 'POST'], 'date-range-or-month-wise-sewing-input', [OrderColorSizeController::class, 'monthWiseInputReport']);
    Route::get('/month-wise-sewing-input-download/{type}/{date_from}/{date_to}/{line_id}', [OrderColorSizeController::class, 'getMonthWiseInputReportDownload']);

    Route::get('input-closing', [InputClosingAndLineController::class, 'getInputClosingReport']);
    Route::get('input-closing-view/{order_id}/{purchase_order_id}/{color_id}', [InputClosingAndLineController::class, 'getInputClosingReportView']);
    Route::get('/input-closing-report-download/{type}/{buyer_id}/{order_id}/{purchase_order_id}/{color_id}', [InputClosingAndLineController::class, 'getInputClosingReportDownload']);

    Route::get('/floor-line-wise-input-report', [LineWiseOverallReportController::class, 'floorLineInputReport']);
    Route::get('floor-line-wise-input-report-download', [LineWiseOverallReportController::class, 'floorLineWiseInputReportDownload']);

    Route::get('/order-wise-sewing-input-report', [OrderColorSizeController::class, 'orderWiseSewingInputReport']);
    Route::get('/order-wise-sewing-input-report-download', [OrderColorSizeController::class, 'orderWiseSewingInputReportDownload']);

    Route::prefix('/line-size-wise-input-report')->group(function () {
        Route::get('/', [LineSizeWiseInputReportController::class, 'index']);
        Route::get('/download', [LineSizeWiseInputReportController::class, 'download']);
    });

    Route::prefix('v2')->group(function () {
        Route::get('daily-input-status', [DateWiseReportController::class, 'getDailyInputReport']);
        Route::get('daily-input-status-download', [DateWiseReportController::class, 'getDailyInputReportDownload']);
    });

    Route::group(['prefix' => 'report'], function () {
        Route::group(['prefix' => 'daily-size-wise-input'], function () {
            Route::get('', [DailySizeWiseInputReportController::class, 'index']);
            Route::get('xls', [DailySizeWiseInputReportController::class, 'excel']);
            Route::get('pdf', [DailySizeWiseInputReportController::class, 'pdf']);
        });
    });
});

Route::middleware(['web'])->group(function () {
    Route::get('/sewing-input-dashboard', SewingInputDashboardController::class);

    // Input Dashboard V2
    Route::get('/input-dashboard-v2', InputDashboardV2Controller::class);
});
