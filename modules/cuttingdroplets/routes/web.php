<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\BundleCardConsApprovalController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\BundleCardConsumptionReportController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\BundleCardGenerationController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\BuyerStyleWiseCuttingReportController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\ChallanController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\ColorSizeSummaryReportController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\CommonController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\CommonReportController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\CuttingDashboardController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\CuttingPlanController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\CuttingProductionReportV2Controller;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\CuttingQcController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\CuttingQtyRequestController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\CuttingRequisitionController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\CuttingScanController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\DailyBasisCuttingReportController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\DailyCuttingBalanceReportController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\DailySizeWiseCuttingReportController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\DayMonthController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\ManualBundleCardGenerationController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\MonthlyCuttingInputReportController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\OrderColourSizeController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\V2\DateWiseProductionReportController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\V2\OrderWiseReportController;
use SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\YearlySummaryReportController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\UserCuttingFloorPlanPermissionController;

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'namespace' => 'SkylarkSoft\GoRMG\Cuttingdroplets\Controllers'], function () {

    // Routes for common tasks
    Route::get('utility/lots', [CommonController::class, 'lots']);
    Route::get('utility/sizes', [CommonController::class, 'sizes']);
    Route::get('utility/orders', [CommonController::class, 'orders']);
    Route::get('utility/garments-items', [CommonController::class, 'garmentsItems']);
    Route::get('utility/last-cutting-no', [CommonController::class, 'lastCuttingNo']);
    Route::get('utility/lots/{orderId}/{colorId}', [CommonController::class, 'lotsByOrderAndColor']);
    Route::get('utility/get-style-by-buyer', [CommonController::class, 'getStyleByBuyer']);
    Route::get('utility/purchase-orders/{order_id}', [CommonController::class, 'purchaseOrdersByOrder']);
    Route::get('utility/purchase-orders', [CommonController::class, 'purchaseOrder']);
    Route::get('utility/item-wise-purchase-orders', [CommonController::class, 'itemWisePurchaseOrders']);
    Route::get('utility/colors', [CommonController::class, 'utilityColors']);
    Route::get('utility/get-colors/{purchaseOrderId}', [CommonController::class, 'getColors']);
    Route::get('utility/colors-by-orders', [CommonController::class, 'colorsByOrder']);
    Route::get('utility/get-buyers-for-select2-search', [CommonController::class, 'getBuyersForSelectSearch']);
    Route::get('utility/get-styles-for-select2-search', [CommonController::class, 'getStylesForSelectSearch']);
    Route::get('utility/get-styles-by-buyer/{buyer_id}', [CommonController::class, 'getStylesByBuyer']);
    Route::get('utility/get-items-for-select2-search', [CommonController::class, 'getItemsForSelectSearch']);
    Route::get('/utility/get-items-by-order/{order_id}', [CommonController::class, 'getItemsByOrder']);
    Route::get('utility/get-pos-for-select2-search', [CommonController::class, 'getPosForSelectSearch']);
    Route::get('/utility/get-colors-for-po-select2-search', [CommonController::class, 'getColorsForPosSelectSearch']);
    Route::get('/utility/get-order-items-smv', [CommonController::class, 'getOrderItemSmv']);
    Route::get('/utility/get-orders-with-booking-no/{buyer_id}', [CommonController::class, 'getOrdersWithBookingNo']);

    // Routes for bundlecard[knit]
    Route::group(['prefix' => 'bundle-card-generations'], function () {
        Route::get('', [BundleCardGenerationController::class, 'index']);
        Route::get('/create', [BundleCardGenerationController::class, 'create']);
        Route::post('', [BundleCardGenerationController::class, 'store']);
        Route::get('/{id}', [BundleCardGenerationController::class, 'show']);
        Route::delete('/{id}', [BundleCardGenerationController::class, 'destroy']);
        Route::post('/{id}/scan', [BundleCardGenerationController::class, 'scanBundleCards']);
        Route::get('/{id}/re-generate', [BundleCardGenerationController::class, 'reGenerateForm']);
        Route::post('/{id}/re-generate', [BundleCardGenerationController::class, 'reGenerate']);
        Route::get('/{id}/print', [BundleCardGenerationController::class, 'print']);
        Route::get('/{id}/update-view-cache', [BundleCardGenerationController::class, 'updateViewCache']);
    });
    Route::get('/bundle-card-generations-update', [BundleCardGenerationController::class, 'updateAll']);
    Route::get('search-bundle-card-generations', [BundleCardGenerationController::class, 'searchBundleCardGenerations']);

    // Route for bundlecard[manual]
    Route::group(['prefix' => 'bundle-card-generation-manual'], function () {
        Route::get('', [ManualBundleCardGenerationController::class, 'index']);
        Route::get('/create', [ManualBundleCardGenerationController::class, 'create']);
        Route::post('', [ManualBundleCardGenerationController::class, 'store']);
        Route::get('/{id}', [ManualBundleCardGenerationController::class, 'show']);
        Route::delete('/{id}', [ManualBundleCardGenerationController::class, 'destroy']);
        Route::post('/{id}/scan', [ManualBundleCardGenerationController::class, 'scanBundleCards']);
        Route::get('/{id}/re-generate', [ManualBundleCardGenerationController::class, 'reGenerateForm']);
        Route::post('/{id}/re-generate', [ManualBundleCardGenerationController::class, 'reGenerate']);
        Route::get('/{id}/print', [ManualBundleCardGenerationController::class, 'print']);
        Route::get('/{id}/update-view-cache', [ManualBundleCardGenerationController::class, 'updateViewCache']);
    });
    Route::get('/search-manual-bundle-card-generations', [ManualBundleCardGenerationController::class, 'searchManualBundleCardGenerations']);

    Route::get('/cutting-requisitions', [CuttingRequisitionController::class, 'index']);
    Route::get('/cutting-requisitions/create', [CuttingRequisitionController::class, 'create']);
    Route::get('/get-fabric-fabric-received-store', [CuttingRequisitionController::class, 'getFabricFabricReceivedStore']);
    Route::post('/cutting-requisitions', [CuttingRequisitionController::class, 'store']);
    Route::get('/cutting-requisitions/{id}/edit', [CuttingRequisitionController::class, 'edit']);
    Route::put('/cutting-requisitions/{id}', [CuttingRequisitionController::class, 'update']);

    Route::get('/cutting-requisitions-delete/{id}', [CuttingRequisitionController::class, 'destroy']);
    Route::get('/cutting-requisitions/{cutting_requisition_id}', [CuttingRequisitionController::class, 'show']);
    Route::get('/cutting-requisition-approved/{cutting_requisition_id}', [CuttingRequisitionController::class, 'cuttingRequisitionApproved']);
    Route::get('/search-cutting-requisitions', [CuttingRequisitionController::class, 'searchCuttingRequisitions']);

    // Roure for cutting scan
    Route::get('/cutting-scan', [CuttingScanController::class, 'cuttingScan']);
    Route::post('/cutting-scan-post', [CuttingScanController::class, 'cuttingScanPost']);
    Route::get('/close-cutting-challan/{cutting_challan_no}', [CuttingScanController::class, 'closeCuttingScan']);
    Route::match(['GET', 'POST'], '/cutting-qc-scan', [CuttingQcController::class, 'cuttingQcScan']);
    Route::post('/cutting-qc-scan-post', [CuttingQcController::class, 'cuttingQcRejectionPost']);
    Route::get('/close-challan/{cutting_qc_challan_no}', [CuttingQcController::class, 'closeChallan']);
    Route::get('/challan-wise-bundle', [ChallanController::class, 'getChallanWiseBundle']);
    Route::match(['GET', 'POST'], '/replace-bundle-card', [BundleCardGenerationController::class, 'replcaeBundleCard']);

    Route::get('/update-cutting-production', [ChallanController::class, 'updateCuttingProduction']);
    Route::delete('/delete-cutting-bundle/{bundle_id}', [ChallanController::class, 'deleteCuttingBundle']);
    Route::post('/update-bundle', [ChallanController::class, 'updateBundle']);

    // Routes for cutting reports
    Route::get('/all-orders-cutting-report', [OrderColourSizeController::class, 'getAllOrderReport']);
    Route::get('/all-orders-cutting-report-download', [OrderColourSizeController::class, 'allOrdersCuttingReportDownload']);
    Route::get('/buyer-wise-cutting-report', [OrderColourSizeController::class, 'getBuyerWiseReport']);
    Route::get('/get-buyer-wise-cutting-report', [OrderColourSizeController::class, 'getBuyerWiseReportData']);
    Route::get('/buyer-wise-cutting-report-download', [OrderColourSizeController::class, 'getBuyerWiseReportDownload']);
    Route::get('/order-wise-cutting-report-download/{type}/{order_id}', [OrderColourSizeController::class, 'getOrderWiseReportDownload']);
    Route::get('/get-order-color-wise-report-download/{type}/{order_id}/{color_id}', [OrderColourSizeController::class, 'getOrderColorWiseReportDownload']);

    Route::get('/order-wise-cutting-report', [OrderColourSizeController::class, 'getOrderWiseReport']);
    Route::get('/get-order-wise-cutting-report/{purchase_order_id}', [OrderColourSizeController::class, 'getOrderWiseReportPost']);
    Route::get('/get-order-color-wise-cutting-report/{purchase_order_id}/{color_id}', [OrderColourSizeController::class, 'getOrderColorWiseReportPost']);
    Route::get('/get-style-wise-report/{order_id}', [OrderColourSizeController::class, 'getStyleWiseReport']);

    Route::get('/color-wise-cutting-summary', [OrderColourSizeController::class, 'getColorWiseSummaryReport']);
    Route::get('/color-wise-cutting-summary-report-data/{order_id}/{color_id}', [OrderColourSizeController::class, 'getColorWiseSummaryReportData']);
    Route::get('/color-wise-cutting-report-download/{type}/{buyer_id}/{order_id}/{color_id}', [OrderColourSizeController::class, 'getColorWiseCuttingReportDownload']);

    Route::get('/excess-cutting-report', [OrderColourSizeController::class, 'getExcessReport']);
    Route::get('/excess-cutting-report-download', [OrderColourSizeController::class, 'excessCuttingReportDownload']);

    Route::get('/daily-cutting-report', [DayMonthController::class, 'getDailyCuttingReport']);
    Route::get('/daily-cutting-report-download/{type}/{date}', [DayMonthController::class, 'getdailyCuttingReportDownload']);

    Route::get('/daily-basis-cutting-report', [DailyBasisCuttingReportController::class, 'index']);
    Route::post('/daily-basis-cutting-report', [DailyBasisCuttingReportController::class, 'getReport']);
    Route::get('/daily-basis-cutting-report/pdf', [DailyBasisCuttingReportController::class, 'pdf']);

    Route::get('/date-wise-cutting-report', [DayMonthController::class, 'getDateWiseReport']);
    Route::get('/date-wise-report-download/{type}/{date}', [DayMonthController::class, 'getDateWiseReportDownload']);

    Route::get('/get-cutting-no/{buyer_id}/{order_id}/{color_id}', [BundleCardGenerationController::class, 'getCuttingNo']);
    Route::get('/get-cutting-nos-by-po-color', [BundleCardGenerationController::class, 'getCuttingNoByPoColor']);

    Route::match(['GET', 'POST'], '/month-wise-cutting-report', [DayMonthController::class, 'getMonthWiseCuttingReport']);

    Route::get('/lot-wise-cutting-report', [DayMonthController::class, 'lotWiseReportForm']);
    Route::get('/get-lot-wise-cutting-report', [DayMonthController::class, 'getLotWiseReportData']);
    Route::get('/lot-wise-cutting-report-download', [DayMonthController::class, 'getLotWiseCuttingReportDownload']);

    Route::get('/get-style-wise-report-download/{type}/{style_id}', [OrderColourSizeController::class, 'getStyleWiseReportDownload']);
    Route::get('/month-wise-cutting-report-download/{type}/{from_date}/{to_date}', [DayMonthController::class, 'getMonthWiseReportDownload']);

    Route::get('/cutting-no-wise-cutting-report', [DayMonthController::class, 'cuttingNoWiseReport']);
    Route::get('/get-cutting-no-wise-cutting-report', [DayMonthController::class, 'getCuttingNoWiseReportData']);
    Route::get('/cutting-no-wise-cutting-report-download', [DayMonthController::class, 'getCuttingNoWiseCuttingReportDownload']);

    Route::get('/monthly-table-wise-cutting-production-summary-report', [DayMonthController::class, 'monthlyTableWiseCuttingProductionSummaryReport']);
    Route::get('/monthly-table-wise-cutting-production-summary-report-download', [DayMonthController::class, 'monthlyTableWiseCuttingProductionSummaryReportDownload']);

    Route::get('/consumption-report', [BundleCardConsumptionReportController::class, 'consumptionReport']);
    Route::get('/consumption-report-download', [BundleCardConsumptionReportController::class, 'consumptionReportDownload']);

    Route::get('/buyer-style-wise-fabric-consumption-report', [OrderColourSizeController::class, 'buyerStyleWiseFabricConsumptionReport']);
    Route::get('/buyer-style-wise-fabric-consumption-report-download', [OrderColourSizeController::class, 'buyerStyleWiseFabricConsumptionReportDownload']);

    Route::get('/daily-fabric-consumption-report', [OrderColourSizeController::class, 'dailyFabricConsumptionReport']);
    Route::get('/daily-fabric-consumption-report-download', [OrderColourSizeController::class, 'dailyFabricConsumptionReportDownload']);

    Route::get('/monthly-fabric-consumption-report', [OrderColourSizeController::class, 'monthlyFabricConsumptionReport']);
    Route::get('/monthly-fabric-consumption-report-download', [OrderColourSizeController::class, 'monthlyFabricConsumptionReportDownload']);

    Route::get('/bundle-scan-check', [CommonReportController::class, 'getBundleScanCheck']);
    Route::get('/bundle-scan-check-data', [CommonReportController::class, 'bundleScanCheckData']);
    Route::get('/bundlecard-scan-check-report-download', [CommonReportController::class, 'getBundlecardScanCheckReportDownload']);

    Route::get('/booking-balance-bundle-scan-check', [CommonReportController::class, 'bookingBalanceBundleScanCheck']);

    Route::get('/order-wise-qc-report', [CuttingQcController::class, 'orderWiseQcReport']);
    Route::get('/order-wise-qc-report-download/{type}', [CuttingQcController::class, 'orderWiseQcReportDownload']);

    Route::get('/get-challans-by-bundlecard', [CommonReportController::class, 'getChallansByBundlecard']);
    Route::get('/individual-bundle-scan-check', [CommonReportController::class, 'individualBundleScanCheck']);

    Route::get('/floor-line-wise-cutting-report', [CommonReportController::class, 'floorLineWiseCuttingReport']);
    Route::get('floor-line-wise-cutting-report-download', [CommonReportController::class, 'floorLineWiseCuttingReportDownload']);

    Route::get('/cutting-production-summary-report', [CommonReportController::class, 'CuttingProductionSummaryReport']);
    Route::get('/cutting-production-summary-report-download', [CommonReportController::class, 'monthlyTableWiseCuttingProductionSummaryReportDownload']);

    // Routes for Cutting Plan
    Route::get('/cutting-plan', [CuttingPlanController::class, 'cuttingPlan']);
    Route::get('/get-cutting-plan-data/{id}', [CuttingPlanController::class, 'getCuttingPlanData']);
    Route::get('/get-buyers-for-dropdown/{factory_id}', [CuttingPlanController::class, 'getBuyersForDropdown']);
    Route::get('/get-cutting-table-for-cutting-plan/{cutting_table_id}', [CuttingPlanController::class, 'getCuttingTableForCuttingPlan']);
    Route::get('/get-cutting-floors-for-factory/{factory_id}', [CuttingPlanController::class, 'getCuttingFloorsForFactory']);
    Route::get('/get-order-list-for-cut-plan/{buyer_id}', [CuttingPlanController::class, 'getOrderList']);
    Route::get('/get-purchase-orders-for-cutting-plan/{order_id}', [CuttingPlanController::class, 'getPurchaseOrderList']);
    Route::get('/get-cutting-plan-user-permission/{cutting_floor_id}/{user_id}', [UserCuttingFloorPlanPermissionController::class, 'getCuttingPlanUserPermission']);
    Route::get('/check-cutting-plan-board-lock/{cutting_floor_id}', [UserCuttingFloorPlanPermissionController::class, 'checkCuttingPlanBoardLock']);

    // Routes for user cutting plan permission
    Route::get('/user-cutting-floor-plan-permissions', [UserCuttingFloorPlanPermissionController::class, 'index']);
    Route::post('/user-cutting-floor-plan-permissions', [UserCuttingFloorPlanPermissionController::class, 'store']);
    Route::get('/update-cutting-plan-board-lock-info', [UserCuttingFloorPlanPermissionController::class, 'updateLockInfo']);

    // Daily Size wise cutting production
    Route::group(['prefix' => '/daily-size-wise-cutting-report'], function () {
        Route::get('/', [DailySizeWiseCuttingReportController::class, 'index']);
        Route::post('/', [DailySizeWiseCuttingReportController::class, 'getReport']);
        Route::get('/pdf', [DailySizeWiseCuttingReportController::class, 'getReportPdf']);
        Route::get('/xls', [DailySizeWiseCuttingReportController::class, 'getReportExcel']);
    });

    // Buyer Style wise cutting production
    Route::group(['prefix' => '/buyer-style-wise-cutting-report'], function () {
        Route::get('/', [BuyerStyleWiseCuttingReportController::class, 'index']);
        Route::post('/', [BuyerStyleWiseCuttingReportController::class, 'getReport']);
        Route::get('/pdf', [BuyerStyleWiseCuttingReportController::class, 'getReportPdf']);
        Route::get('/xls', [BuyerStyleWiseCuttingReportController::class, 'getReportExcel']);
    });

    // Routes for Color Size Summary Report
    Route::get('/color-size-summary-report', [ColorSizeSummaryReportController::class, 'index']);
    Route::post('/color-size-summary-report/get-report', [ColorSizeSummaryReportController::class, 'getReport']);
    Route::get('/color-size-summary-report/get-report-xls', [ColorSizeSummaryReportController::class, 'getReportExcel']);

    // Cutting Production Report V2
    Route::group(['prefix' => '/cutting-production-report-v2'], function () {
        Route::get("/", [CuttingProductionReportV2Controller::class, 'index']);
        Route::post("/", [CuttingProductionReportV2Controller::class, 'getReport']);
        Route::get("/xls", [CuttingProductionReportV2Controller::class, 'getReportExcel']);
        Route::get("/pdf", [CuttingProductionReportV2Controller::class, 'getReportPdf']);
    });

    // Monthly Cutting Input Report
    Route::group(['prefix' => '/monthly-cutting-input-report'], function () {
        Route::get("/", [MonthlyCuttingInputReportController::class, 'index']);
        Route::post("/", [MonthlyCuttingInputReportController::class, 'getReport']);
        Route::get("/xls", [MonthlyCuttingInputReportController::class, 'getReportExcel']);
        Route::get("/pdf", [MonthlyCuttingInputReportController::class, 'getReportPdf']);
    });

    // Yearly Summary Report
    Route::group(['prefix' => '/yearly-summary-report'], function () {
        Route::get("/", [YearlySummaryReportController::class, 'index']);
        Route::post("/", [YearlySummaryReportController::class, 'getReport']);
        Route::get("/xls", [YearlySummaryReportController::class, 'getReportExcel']);
        Route::get("/pdf", [YearlySummaryReportController::class, 'getReportPdf']);
    });

    // Daily cutting balance report
    Route::group(['prefix' => 'daily-cutting-balance-report'], function () {
        Route::get('/', [DailyCuttingBalanceReportController::class, 'index']);
        Route::get('/pdf', [DailyCuttingBalanceReportController::class, 'pdf']);
        Route::get('/xls', [DailyCuttingBalanceReportController::class, 'excel']);
    });
    Route::group(['prefix' => '/v2'], function () {
        Route::get('/date-wise-cutting-report', [DateWiseProductionReportController::class, 'dateWiseReport']);
        Route::get('/date-wise-report-download/{type}/{date}', [DateWiseProductionReportController::class, 'dateWiseReportDownload']);
        Route::get('/all-orders-cutting-report', [OrderWiseReportController::class, 'getAllOrderReport']);
        Route::get('/all-orders-cutting-report-download', [OrderWiseReportController::class, 'allOrdersCuttingReportDownload']);
    });

    // Cutting Quantity Request
    Route::prefix('cutting-qty-request')->group(function () {
        Route::get('/', [CuttingQtyRequestController::class, 'index']);
        Route::post('/', [CuttingQtyRequestController::class, 'store']);
        Route::get('/search/matrix', [CuttingQtyRequestController::class, 'searchMatrix']);
    });

    Route::get('/bundle-card/{id}/cons-approval', [BundleCardConsApprovalController::class, '__invoke']);
});

Route::group(['prefix' => 'api', 'middleware' => 'api'], function () {

    Route::delete('/cutting-plans/{floor_id}/{plan_date}/{user_id}/{id}', [CuttingPlanController::class, 'destroy']);
    Route::put('/cutting-plans/{floor_id}/{plan_date}/{user_id}/{id}', [CuttingPlanController::class, 'update']);
    Route::resource('/cutting-plans/{floor_id}/{plan_date}/{user_id}/', CuttingPlanController::class);
});
Route::group(['middleware' => 'web'], function () {
    Route::get('/cutting-dashboard', [CuttingDashboardController::class, '__invoke']);
});
