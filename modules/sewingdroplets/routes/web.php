<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Sewingdroplets\Controllers\CapacityPlan\CapacityAvailabilityAPIController;
use SkylarkSoft\GoRMG\Sewingdroplets\Controllers\CapacityPlan\CapacityDefaultsAPIController;
use SkylarkSoft\GoRMG\Sewingdroplets\Controllers\CapacityPlan\CapacityPlanEntryAPIController;
use SkylarkSoft\GoRMG\Sewingdroplets\Controllers\FactoryWiseSewingFloorAPIController;
use SkylarkSoft\GoRMG\Sewingdroplets\Controllers\GarmentsItemAPIController;
use SkylarkSoft\GoRMG\Sewingdroplets\Controllers\SewingLineAPIController;
use SkylarkSoft\GoRMG\Sewingdroplets\Controllers\SewingOutputScanController;

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'namespace' => 'SkylarkSoft\GoRMG\Sewingdroplets\Controllers'], function () {

    Route::get('/sewing-output-scan', [SewingOutputScanController::class, 'sewingOutputScanForm']);
    Route::post('/sewing-output-scan-post', [SewingOutputScanController::class, 'sewingOutputScanPost']);
    Route::get('/sewing-rejection/{bundle_id}', [SewingOutputScanController::class, 'sewingRejection']);
    Route::post('/sewing-rejection-post', [SewingOutputScanController::class, 'sewingRejectionPost']);
    Route::get('sewing-close-challan/{output_challan_no}', [SewingOutputScanController::class, 'sewingChallanClose']);
    Route::get('sewingoutput-challan-list', [SewingOutputScanController::class, 'sewingoutputChallanList']);
    Route::get('view-sewingoutput-challan/{output_challan_no}', [SewingOutputScanController::class, 'viewSewingoutputChallan']);

    // Route for sewing output report
    Route::get('/all-orders-sewing-output-summary', 'OrderColorSizeController@orderWiseReport');
    Route::get('/all-orders-sewing-output-report-download', 'OrderColorSizeController@allOrdersSewingOutputReportDownload');

    Route::get('/buyer-wise-sewing-output', 'OrderColorSizeController@getBuyerWiseReport');
    Route::get('/get-buyer-wise-sewing-output-data', 'OrderColorSizeController@getBuyerWiseReportSewingOutput');
    Route::get('/buyer-wise-sewing-output-report-download/{type}/{buyer_id}/{order_id}/{page}', 'OrderColorSizeController@getBuyerWiseReportDownload');

    Route::get('/order-wise-sewing-output', 'OrderColorSizeController@orderWiseReportForm');
    Route::get('/order-wise-sewing-output-report/{order_id}', 'OrderColorSizeController@orderWiseReportView');
    Route::get('/order-wise-sewing-output-report-download/{type}/{order_id}', 'OrderColorSizeController@orderWiseSewingOutputReportDownload');

    Route::get('/get-style-wise-sewing-output/{style_id}', 'OrderColorSizeController@getStyleWiseSewingOutputReport');
    Route::get('/get-style-wise-sewing-output-report-download/{type}/{style_id}', 'OrderColorSizeController@getStyleWiseSewingOutputReportDownload');

    Route::get('/floor-line-wise-sewing-report', 'LineWiseOutputController@floorLineWiseSewingReport');
    Route::get('floor-line-wise-sewing-report-download', 'LineWiseOutputController@floorLineWiseSewingReportDownload');

    Route::get('/line-wise-hourly-sewing-output-report-download/{type}', 'LineWiseOutputController@getLineHourlyWiseOutputReportDownload');

    Route::get('/line-wise-hourly-sewing-output', 'LineWiseOutputController@getDateWiseLineHourlyWiseOutput');
    Route::get('/date-wise-hourly-sewing-output', 'LineWiseOutputController@getDateWiseLineHourlyWiseOutput');
    Route::get('/date-wise-hourly-sewing-output-report-download', 'LineWiseOutputController@getDateWiseLineHourlyWiseOutputReportDownload');

    Route::get('/date-wise-sewing-output', 'DateAndDateRangeController@getDateRangeWiseReportForm');
    Route::match(['GET', 'POST'], '/date-wise-sewing-output-post', 'DateAndDateRangeController@getDateRangeWiseReportPost');
    Route::get('/date-wise-sewing-output-report-download', 'DateAndDateRangeController@getDateRangeWiseReportDownload');

    Route::get('/line-date-wise-output-avg', 'DateAndDateRangeController@getLineDateWiseAvgForm');
    Route::get('/line-date-wise-output-avg-report', 'DateAndDateRangeController@getLineDateWiseAvgReport');
    Route::get('/line-date-wise-output-avg-report-download/{type}/{order_id}/{purchase_order_id?}', 'DateAndDateRangeController@getLineDateWiseAvgReportDownload');

    Route::get('/production-board', 'LineWiseOutputController@productionBoard');

    Route::get('/bundle-wise-qc', [SewingOutputScanController::class, 'bundleWiseQc']);

    Route::get('/daily-input-output-report', 'DateAndDateRangeController@dailyInputOutputReport');
    Route::get('/daily-input-output-report-download/{type}/{buyer_id}/{order_id}', 'DateAndDateRangeController@dailyInputOutputReportDownload');

    Route::get('/monthly-line-wise-production-summary-report', 'DateAndDateRangeController@monthlyLineWiseProductionSummaryReport');
    Route::get('/monthly-line-wise-production-summary-report-download', 'DateAndDateRangeController@monthlyLineWiseProductionSummaryReportDownload');

    Route::get('/sewing-line-plan-report', 'SewingPlanReportController@sewingLinePlanReport');
    Route::get('/sewing-line-plan-report-download', 'SewingPlanReportController@sewingLinePlanReportDownload');

    Route::get('/hourly-sewing-production-against-target-data', 'HourlySewingProductionController@hourlySewingProductionAgainstTargetData');

    Route::get('/sewing-forcast-report', 'LineWiseOutputController@dailySewingForecastReport');
    Route::get('/sewing-forcast-report-download', 'LineWiseOutputController@dailySewingForecastReportDownload');
});

Route::group(['middleware' => ['web'], 'namespace' => 'SkylarkSoft\GoRMG\Sewingdroplets\Controllers'], function () {
    Route::get('/production-dashboard', 'ProductionDashboardController@productionDashboard');
    Route::get('/production-dashboard-v2', 'ProductionDashboardController@productionDashboardV2');
    Route::get('/production-dashboard-v3', 'ProductionDashboardController@productionDashboardV3');
    Route::get('/production-dashboard-v4', 'ProductionDashboardController@productionDashboardV4');
    Route::get('/production-dashboard-v5', 'ProductionDashboardController@productionDashboardV5');
});

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'namespace' => 'SkylarkSoft\GoRMG\Sewingdroplets\Controllers'], function () {
    // Sewing Line Planning Capacity Entry related Routes
    Route::get('/line-capacity-entry', 'SewingLineCapacityController@lineCapacityEntry');
    Route::post('/line-capacity-entry-action', 'SewingLineCapacityController@lineCapacityEntryPost');
    Route::get('/get-floor-wise-capacity-entry-form/{floor_id}', 'SewingLineCapacityController@getLineCapacityEntryForm');
    Route::get('/get-line-capacity-information/{line_id}/{smv}', 'SewingLineCapacityController@getLineCapacityInformation');

    // Capacity Inquiry
    Route::get('/order-wise-capacity-inquiry', 'SewingLineCapacityController@orderWiseCapacityInquiry');
    Route::get('/get-po-capacity-section', 'SewingLineCapacityController@getPoCapacitySection');
    Route::get('/get-floor-capacity-section', 'SewingLineCapacityController@getFloorCapacitySection');
    Route::get('/get-line-capacity-section', 'SewingLineCapacityController@getLineCapacitySection');

    // Sewing Plan Related Routes
    Route::get('/sewing-plan', 'SewingPlanController@sewingPlan');
    Route::delete('/sewing-plan/{id}/delete', 'SewingPlanController@sewingPlanDelete');
    Route::get('/get-lines-for-sewing-plan', 'SewingPlanController@getLinesForSewingPlan');
    Route::get('/get-sewing-floors-for-factory/{factory_id}', 'SewingPlanController@getSewingFloorsForFactory');
    Route::get('/get-orders-for-factory/{factory_id}', 'SewingPlanController@getOrdersForFactory');
    Route::get('/get-po-info/{id}', 'SewingPlanController@getPurchaseOrderInfo');
    Route::get('/get-plan-create-form', 'SewingPlanController@getCreatePlanForm');
    Route::get('/get-end-date-time-for-plan/{start_date}/{smv}/{line_id}/{allocated_qty}', 'SewingPlanController@getEndDateTimeForPlan');
    Route::post('/sewing-plan-event-create', 'SewingPlanController@sewingPlanEventCreate');
    Route::get('/get-sewing-plan-order-details/{plan_id}', 'SewingPlanController@getSewingPlanOrderDetails');
    Route::post('/sewing-plan-note-update/{plan_id}', 'SewingPlanController@sewingPlanNoteUpdate');
    Route::post('/sewing-plan-split', 'SewingPlanController@sewingPlanSplit');
    Route::post('/sewing-plan-line-change', 'SewingPlanController@sewingPlanLineChange');
    Route::post('/sewing-plan-strip-lock-unlock/{sewing_plan_id}', 'SewingPlanController@sewingPlanStripLockUnlock');
    Route::post('/pull-strip', 'SewingPlanController@pullStrip');
    Route::post('/push-strip', 'SewingPlanController@pushStrip');
    Route::post('/undo-sewing-plan', 'SewingPlanController@undoSewingPlan');
    Route::post('/redo-sewing-plan', 'SewingPlanController@redoSewingPlan');

    // Working Hour Update
    Route::get('/get-sewing-working-hours-date-form/{wh_year}/{wh_month}', 'SewingWorkingHourController@getWorkingHoursDateForm');
    Route::post('/sewing-section-working-hour-update', 'SewingWorkingHourController@sewingSectionWorkingHourUpdate');

    // Load List related routes
    Route::get('/get-load-list-modal-content', 'LoadListController@getLoadListModalContent');
    Route::post('/generate-load-list', 'LoadListController@generateLoadList');

    // sewing holidays routes
    Route::get('/sewing-holidays', 'SewingHolidayController@index');
    Route::get('/sewing-holidays/create', 'SewingHolidayController@create');
    Route::post('/sewing-holidays', 'SewingHolidayController@store');
    Route::get('/sewing-holidays/{id}/update', 'SewingHolidayController@edit');
    Route::put('/sewing-holidays/{id}', 'SewingHolidayController@update');
    Route::delete('/sewing-holidays/{id}', 'SewingHolidayController@destroy');
    Route::get('/sewing-holidays/search', 'SewingHolidayController@search');
    Route::get('/get-sewing-holidays', 'SewingHolidayController@getSewingHolidays');
});

Route::group(['prefix' => 'api', 'middleware' => 'api', 'namespace' => 'SkylarkSoft\GoRMG\Sewingdroplets\Controllers'], function () {

    Route::delete('/sewing-plans/{id}', 'SewingPlanController@destroy');
    Route::put('/sewing-plans/{user_id}/{factory_id}/{id}', 'SewingPlanController@update');
    Route::resource('/sewing-plans/{user_id}/{factory_id}/', 'SewingPlanController');
});

Route::get('/api/sewing-floor/{factoryId}', FactoryWiseSewingFloorAPIController::class);
Route::get('/api/fetch-lines', SewingLineAPIController::class);
Route::get('/api/fetch-items', GarmentsItemAPIController::class);

//Route::group(['prefix' => 'planning', 'middleware' => 'web'], function () {
//    Route::view('/capacity-planning-entry', 'sewingdroplets::capacity-planning.capacity-planning-entry');
//    Route::view('/capacity-availability', 'sewingdroplets::capacity-planning.capacity-availability');
//});

//Route::group(['prefix' => 'api/v1/planning', 'middleware' => ['web', 'auth', 'menu-auth']], function () {
//    Route::get('defaults', CapacityDefaultsAPIController::class);
//    Route::group(['prefix' => 'capacity-plan'], function () {
//        Route::post('search/line-wise', [CapacityPlanEntryAPIController::class, 'searchCapacityPlan']);
//        Route::post('', [CapacityPlanEntryAPIController::class, 'save']);
//        Route::delete('/{factoryCapacity}', [CapacityPlanEntryAPIController::class, 'delete']);
//    });
//
//    Route::group(['prefix' => 'capacity-availability'], function () {
//        Route::post('search/capacity', [CapacityAvailabilityAPIController::class, 'searchCapacity']);
//    });
//});
