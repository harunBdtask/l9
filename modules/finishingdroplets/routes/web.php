<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\ColorAndSizeReportController;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\ErpPackingListController;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\ErpPackingListControllerV2;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\FinishingProductionReportController;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\FinishingProductionReportV2Controller;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\FinishingProductionReportV3Controller;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\FinishingTargetController;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\HourlyFinishingProductionController;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\HourlyFinishingProductionReportController;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\IronPolyPackingController;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\MonthlyTotalReceivedFinishingController;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\PackingAndGetUpController;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\PackingListV3Controller;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\PolyCartoonReportController;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\PoWiseReportController;
use SkylarkSoft\GoRMG\Finishingdroplets\Controllers\ShipmentController;

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'namespace' => 'SkylarkSoft\GoRMG\Finishingdroplets\Controllers'], function () {
    Route::get('/packing-list-generate', [PackingAndGetUpController::class, 'packingListGenerateForm']);
    Route::get('/packing-list-generate-view/{order_id}/{color_id}', [PackingAndGetUpController::class, 'packingListGenerateView']);
    Route::post('/packing-list-generate-action', [PackingAndGetUpController::class, 'packingListGenerateAction']);
    Route::get('/packing-view/{challan_no}', [PackingAndGetUpController::class, 'packingView']);

    Route::get('/update-getup-production', [PackingAndGetUpController::class, 'updateGetupProduction']);
    Route::get('/update-getup-production-form/{order_id}/{color_id}', [PackingAndGetUpController::class, 'updateGetupProductionForm']);
    Route::post('/update-getup-production-action', [PackingAndGetUpController::class, 'updateGetupProductionAction']);

    // Route for poly & cartoon
    Route::get('/iron-poly-packings', [IronPolyPackingController::class, 'index']);
    Route::get('/iron-poly-packings/create', [IronPolyPackingController::class, 'create']);
    Route::post('/iron-poly-packings', [IronPolyPackingController::class, 'store']);
    Route::get('/iron-poly-packings/{id}/edit', [IronPolyPackingController::class, 'edit']);
    Route::put('/iron-poly-packings/{id}', [IronPolyPackingController::class, 'update']);
    Route::get('/delete-iron-poly-packings/{id}', [IronPolyPackingController::class, 'destroy']);
    Route::get('/get-orders-for-iron-poly-packings/{order_id}', [IronPolyPackingController::class, 'getOrdersForPoly']);

    Route::get('/finishing-receieved-report', [ColorAndSizeReportController::class, 'getFinishingReceivedReportForm']);
    Route::get('/finishing-report-order-wise-view/{order_id}', [ColorAndSizeReportController::class, 'finishingReportColorWise']);
    Route::get('/finishing-receieved-report-download/{type}/{order_id}', [ColorAndSizeReportController::class, 'finishingReceivedReportDownload']);
    Route::get('/order-wise-finishing-report', [ColorAndSizeReportController::class, 'orderWiseFinishingReportForm']);
    Route::get('/order-wise-finishing-report-action/{order_id}', [ColorAndSizeReportController::class, 'orderWiseFinishingReport']);
    Route::get('/color-wise-finishing-report', [ColorAndSizeReportController::class, 'colorWiseFinishingReport']);
    Route::get('/color-wise-finishing-report-action/{order_id}/{color_id}', [ColorAndSizeReportController::class, 'colorWiseFinishingReportAction']);
    Route::get('/color-wise-finishing-report-download/{type}/{order_id}', [ColorAndSizeReportController::class, 'colorWiseFinishingReportDownload']);
    Route::get('/size-wise-finishing-report-download/{type}/{order_id}/{color_id}', [ColorAndSizeReportController::class, 'sizeWiseFinishingReportDownload']);

    Route::get('/date-wise-finishing-report', [ColorAndSizeReportController::class, 'dateWiseFinishingReport']);
    Route::match(['GET', 'POST'], '/date-wise-finishing-report-post', [ColorAndSizeReportController::class, 'dateWiseFinishingReportPostAction']);
    Route::get('/date-wise-finishing-report-download/{type}/{from_date}/{to_date}', [ColorAndSizeReportController::class, 'dateWiseFinishingReportDownload']);

    Route::get('/all-orders-poly-cartoon-report', [PolyCartoonReportController::class, 'getAllOrdersReport']);
    Route::get('/date-wise-iron-poly-packing-summary', [PolyCartoonReportController::class, 'dateWiseIronPolyPackingSummary']);

    Route::get('/all-orders-poly-cartoon-report-download/{type}', [PolyCartoonReportController::class, 'getAllOrdersReportDownload']);
    Route::get('/date-wise-iron-poly-packing-summary-report-download/{type}/{from_date}/{to_date}', [PolyCartoonReportController::class, 'dateWiseIronPolyPackingSummaryReportDownload']);

    Route::match(['GET', 'POST'], '/po-shipment-status', [PoWiseReportController::class, 'poShipmentStatus']);
    Route::get('/po-shipment-status-report-download/{buyer_id}/{order_id}/{current_page}/{type}', [PoWiseReportController::class, 'poShipmentStatusReportDownload']);

    Route::get('/finishing-production-status', [PoWiseReportController::class, 'finishingProductionStatusReport']);
    Route::get('/finishing-production-status-report-download/{buyer_id}/{style_id}/{type}', [PoWiseReportController::class, 'finishingProductionStatusReportDownload']);

    Route::get('/shipment-status-update', [ShipmentController::class, 'updateShipmentStatus']);
    Route::get('/shipment-status-approval/{order_id}', [ShipmentController::class, 'shipmentStatusApproval']);

    Route::match(['GET', 'POST'], '/daily-finishing-production-report', [FinishingProductionReportController::class, 'dailyFinishingProductionReport']);
    Route::get('/daily-finishing-production-report-download/{type}/{date}', [FinishingProductionReportController::class, 'dailyFinishingProductionReportDownload']);

    Route::get('/date-wise-finishing-summary-report', [FinishingProductionReportController::class, 'dateWiseFinishingSummaryReport']);
    Route::get('/date-wise-finishing-summary-report-download', [FinishingProductionReportController::class, 'dateWiseFinishingSummaryReportDownload']);

    Route::get('/finishing-summary-report', [FinishingProductionReportController::class, 'finishingSummaryReport']);
    Route::get('/finishing-summary-report-download', [FinishingProductionReportController::class, 'finishingSummaryReportDownload']);

    Route::get('/style-wise-finishing-summary-report', [FinishingProductionReportController::class, 'styleWiseFinishingSummaryReport']);
    Route::get('/style-wise-finishing-summary-report-download', [FinishingProductionReportController::class, 'styleWiseFinishingSummaryReportDownload']);

    Route::get('/monthly-total-received-finishing-report', [MonthlyTotalReceivedFinishingController::class, 'monthlyTotalReceivedFinishingReport']);
    Route::post('/monthly-total-received-finishing-report', [MonthlyTotalReceivedFinishingController::class, 'getMonthlyTotalReceivedFinishing']);
    Route::get('/monthly_total_received_finishing/pdf', [MonthlyTotalReceivedFinishingController::class, 'getReportPdf']);
    Route::get('monthly_total_received_finishing/xls', [MonthlyTotalReceivedFinishingController::class, 'getReportExcel']);

    Route::group(['prefix' => 'date-wise-finishing-target'], function () {
        Route::get('/', [FinishingTargetController::class, 'index']);
        Route::get('/list', [FinishingTargetController::class, 'getList']);
        Route::post('/', [FinishingTargetController::class, 'store']);
        Route::delete('/{id}', [FinishingTargetController::class, 'destroy']);
    });

    Route::group(['prefix' => 'hour-wise-finishing-production'], function () {
        Route::get('/', [HourlyFinishingProductionController::class, 'index']);
        Route::get('/list', [HourlyFinishingProductionController::class, 'getList']);
        Route::post('/view', [HourlyFinishingProductionController::class, 'view']);
        Route::post('/', [HourlyFinishingProductionController::class, 'store']);
        Route::post('/delete', [HourlyFinishingProductionController::class, 'destroy']);
    });

    Route::get('/fetch-garments-production-entry-option', [FinishingTargetController::class, 'fetchGarmentsProductionEntryOption']);

    Route::group(['prefix' => 'hourly-finishing-production-report'], function () {
        Route::get('/', [HourlyFinishingProductionReportController::class, 'index']);
        Route::get('/dashboard', [HourlyFinishingProductionReportController::class, 'dashboard']);
        Route::get('/get-report', [HourlyFinishingProductionReportController::class, 'getReport']);
        Route::get('/get-report/pdf', [HourlyFinishingProductionReportController::class, 'getReportPdf']);
        Route::get('/get-report/excel', [HourlyFinishingProductionReportController::class, 'getReportExcel']);
    });

    Route::group(['prefix' => 'finishing-production-report-v2'], function () {
        Route::get('/', [FinishingProductionReportV2Controller::class, 'index']);
        Route::post('/', [FinishingProductionReportV2Controller::class, 'getReport']);
        Route::get('/pdf', [FinishingProductionReportV2Controller::class, 'getReportPdf']);
        Route::get('/excel', [FinishingProductionReportV2Controller::class, 'getReportExcel']);
    });

    Route::group(['prefix' => 'finishing-production-report/v3'], function () {
        Route::get('/', [FinishingProductionReportV3Controller::class, 'index']);
        Route::post('/report', [FinishingProductionReportV3Controller::class, 'report']);
    });

    Route::group(['prefix' => 'erp-packing-list'], function () {
        Route::get('/create', [ErpPackingListController::class, 'create']);
        Route::get('/fetch-po-details', [ErpPackingListController::class, 'fetchPoDetails']);
        Route::delete('/{erpPackingList}', [ErpPackingListController::class, 'destroy']);
        Route::get('/{erpPackingList}', [ErpPackingListController::class, 'show']);
        Route::put('/{erpPackingList}', [ErpPackingListController::class, 'update']);
        Route::post('/', [ErpPackingListController::class, 'store']);
        Route::get('/', [ErpPackingListController::class, 'index']);
        Route::get('/{id}/edit', [ErpPackingListController::class, 'create']);
    });

    Route::group(['prefix' => 'erp-packing-list-v2'], function () {
        Route::get('/', [ErpPackingListControllerV2::class, 'index']);
        Route::get('/create', [ErpPackingListControllerV2::class, 'create']);
        Route::get('/get-po-details', [ErpPackingListControllerV2::class, 'getPoDetails']);
        Route::get('/get-style/{factoryId}/{buyerId}', [ErpPackingListControllerV2::class, 'getStyle']);
        Route::get('/get-po', [ErpPackingListControllerV2::class, 'getPo']);
        Route::post('/store', [ErpPackingListControllerV2::class, 'store']);
        Route::get('/get-edit-po-details/{uid}', [ErpPackingListControllerV2::class, 'getEditPoDetails']);
        Route::put('/{uid}', [ErpPackingListControllerV2::class, 'update']);
        Route::get('/{uid}', [ErpPackingListControllerV2::class, 'view']);
        Route::get('/pdf/{uid}', [ErpPackingListControllerV2::class, 'pdf']);
        Route::get('/excel/{uid}', [ErpPackingListControllerV2::class, 'excel']);
        Route::delete('/destroy/{uid}', [ErpPackingListControllerV2::class, 'destroy']);
    });

    Route::group(['prefix' => 'erp-packing-list-v3'], function () {
        Route::get('', [PackingListV3Controller::class, 'index']);
        Route::get('/create', [PackingListV3Controller::class, 'create']);
        Route::get('/{id}/edit', [PackingListV3Controller::class, 'create']);
        Route::post('', [PackingListV3Controller::class, 'store']);
        Route::put('/{garmentPackingProduction}', [PackingListV3Controller::class, 'update']);
        Route::delete('/{garmentPackingProduction}', [PackingListV3Controller::class, 'destroy']);
        Route::get('/{garmentPackingProduction}', [PackingListV3Controller::class, 'show']);
        Route::get('/excel/{garmentPackingProduction}', [PackingListV3Controller::class, 'excel']);

        Route::group(['prefix' => 'api'], function () {
            Route::get('/search', [PackingListV3Controller::class, 'search']);
            Route::get('/{garmentPackingProduction}/edit', [PackingListV3Controller::class, 'edit']);
            Route::get('/default-values', [PackingListV3Controller::class, 'getDefaultValues']);
        });
    });
});
