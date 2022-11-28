<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Iedroplets\Controllers\ContainerPlannings\ContainerProfileController;
use SkylarkSoft\GoRMG\Iedroplets\Controllers\OperationBulletinCotrolller;
use SkylarkSoft\GoRMG\Iedroplets\Controllers\Reports\WeeklyShipmentScheduleReportController;
use SkylarkSoft\GoRMG\Iedroplets\Controllers\V2\CuttingTargetController;
use SkylarkSoft\GoRMG\Iedroplets\Controllers\V2\SewingLineTargetController;

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'namespace' => 'SkylarkSoft\GoRMG\Iedroplets\Controllers'], function () {

    Route::get('/date-wise-cutting-targets', 'CuttingTargetController@dateWiseCuttingTargets');
    Route::get('/get-cutting-target-form/{floor_id}', 'CuttingTargetController@dateWiseCuttingTargetsFrom');
    Route::post('/date-wise-cutting-target-post', 'CuttingTargetController@dateWiseCuttingTargetsPost');
    Route::get('/get-line-target-form/{floor_id}/{target_date}', 'SewingLineTargetController@getLineTargetForm');
    Route::get('/sewing-line-target', 'SewingLineTargetController@index');
    Route::get('/line-wise-npt', 'SewingLineTargetController@getFloorWiseNpt');
    Route::get('/get-line-wise-npt-update-form/{floor_id}', 'SewingLineTargetController@getLineWiseNptUpdateForm');
    Route::post('/get-line-wise-npt-update-action', 'SewingLineTargetController@lineWiseNptUpdateAction');
    Route::post('/line-target-action', 'SewingLineTargetController@lineTargetAction');
    Route::post('/add-line-to-todays-sewing-taget', 'SewingLineTargetController@addlineToTodaysSewingTarget');
    Route::get('/sewing-line-target-download/{type}/{target_date}', 'SewingLineTargetController@sewingLineTargetDownload');

    Route::group(['prefix' => 'v2'], function () {
        Route::get('/sewing-line-target', [SewingLineTargetController::class, 'index']);
        Route::get('/get-line-target-form/{floor_id}/{target_date}', [SewingLineTargetController::class, 'getLineTargetForm']);
        Route::post('/line-target-action', [SewingLineTargetController::class, 'lineTargetAction']);

        Route::group(['prefix' => 'date-wise-cutting-targets'], function () {
            Route::get('/', [CuttingTargetController::class, 'index']);
            Route::get('/fetch', [CuttingTargetController::class, 'fetchCuttingTarget']);
            Route::post('/', [CuttingTargetController::class, 'store']);
        });

    });

    Route::get('/show-smv', 'SmvController@showSmv');
    Route::post('/get-smv-orders', 'SmvController@getSmvOrders');
    Route::post('/update-order-smv/{id}', 'SmvController@updateOrderSmv');

    Route::get('/shipment-date-and-unit-price-update', 'InspectionController@getInspectionDateAndUnitPriceUpdate');
    Route::get('/get-purchase-orders/{orderId}', 'InspectionController@getPurchaseOrders');
    Route::post('/inspection-date-and-unit-price-update-post', 'InspectionController@getInspectionDateAndUnitPriceUpdatePost');

    Route::get('/shipments', 'ShipmentController@index');
    Route::get('/shipments/create', 'ShipmentController@create');
    Route::post('/shipments', 'ShipmentController@store');
    Route::delete('/shipments/{id}', 'ShipmentController@destroy');
    Route::get('/search-shipments', 'ShipmentController@searchShipments');
    Route::get('/get-shipment-color-wise-size/{order_id}/{color_id}', 'ShipmentController@getShipmentStatusUpdateForm');
    Route::get('/get-shipment-po-information/{style_id}', 'ShipmentController@getShipmentPoInformation');

    /*Route::post('/shipment-status-inspection-date-post', 'ShipmentController@getShipmentStatusUpdatePost');
    Route::get('/get-shipment-po-information/{style_id}', 'ShipmentController@getShipmentPoInformation');*/

    // Route for reports
    Route::get('/all-orders-shipment-summary', 'ShipmentReportController@getAllOrdersShipmentSummary');
    Route::get('/all-orders-shipment-summary-report-download', 'ShipmentReportController@getAllOrdersShipmentSummaryReportDownload');
    Route::get('/buyer-wise-shipment-report', 'ShipmentReportController@getBuyerShipment');
    Route::get('/get-buyer--wise-shipment-report/{buyer_id}', 'ShipmentReportController@getBuyerWiseShipmentReport');
    Route::get('/buyer-wise-shipment-report-download/{type}/{buyer_id}', 'ShipmentReportController@getBuyerWiseShipmentReportDownload');

    // Route for inspection schedule
    Route::get('/next-schedule', 'NextScheduleController@nextSchedule');
    Route::get('/get-line-wise-next-schedule/{floor_id}', 'NextScheduleController@getLineWiseNextSchedule');
    Route::post('/line-wise-inspection-schedule-post', 'NextScheduleController@postLineWiseNextSchedule');
    // Route::post('/inspection-schedule', 'NextScheduleController@store');

    Route::get('/inspection-schedule-date-and-quantity-update', 'InspectionController@inspectionDateAndQuantityUpdate');
    Route::post('/inspection-schedule-and-quantity-update-post', 'InspectionController@inspectionScheduleDateAndQuantityUpdatePost');
    Route::get('/get-styles-for-inspection-update/{order_id}', 'InspectionController@getOrdersForInspectionUpdate');
    Route::post('/inspection-schedule-status-update-post', 'InspectionController@inspectionScheduleStatusUpdatePost');
    //Route::get('/next-schedule-update', 'NextScheduleController@productionBoard');

    /* operation bulletine related routes */
    Route::group(['prefix' => 'operation-bulletins'], function () {
        Route::get('', [OperationBulletinCotrolller::class, 'index']);
        Route::get('/create', [OperationBulletinCotrolller::class, 'create']);
        Route::post('', [OperationBulletinCotrolller::class, 'store']);
        Route::get('/{id}/edit', [OperationBulletinCotrolller::class, 'edit']);
        Route::put('/{id}', [OperationBulletinCotrolller::class, 'update']);
        Route::delete('/{id}', [OperationBulletinCotrolller::class, 'destroy']);
    });
    Route::get('/search-operation-bulletins', [OperationBulletinCotrolller::class, 'searchOperationBulletin']);
    Route::get('/operation-bulletins-view', [OperationBulletinCotrolller::class, 'show']);
    Route::get('/operation-bulletin-download/{id}', [OperationBulletinCotrolller::class, 'download']);
    Route::get('/operation-bulletins-copy/{id}', [OperationBulletinCotrolller::class, 'copyOperationBulletin']);
    Route::post('/operation-bulletins-copy-post/{id}', [OperationBulletinCotrolller::class, 'copyOperationBulletinPost']);

    Route::get('daily-shipment-report', 'ShipmentReportControllerExt@dailyShipment');
    Route::get('overall-shipment-report', 'ShipmentReportControllerExt@overallShipment');

    Route::group(['prefix' => '/weekly-shipment-schedule', 'namespace' => 'Reports'], function () {
        Route::get('/', [WeeklyShipmentScheduleReportController::class, 'index']);
        Route::get('/get-week-of-the-year', [WeeklyShipmentScheduleReportController::class, 'getWeekOfTheYear']);
        Route::post('/', [WeeklyShipmentScheduleReportController::class, 'reportData']);
        Route::get('/pdf', [WeeklyShipmentScheduleReportController::class, 'reportPdf']);
        Route::get('/excel', [WeeklyShipmentScheduleReportController::class, 'reportExcel']);
    });

//    Route::group(['prefix' => '/container-profiles'], function () {
//        Route::get('', [ContainerProfileController::class, 'index']);
//        Route::post('', [ContainerProfileController::class, 'store']);
//        Route::get('/{containerProfile}/edit', [ContainerProfileController::class, 'edit']);
//        Route::put('/{containerProfile}', [ContainerProfileController::class, 'update']);
//        Route::delete('/{containerProfile}', [ContainerProfileController::class, 'destroy']);
//        Route::get('/{any?}', [ContainerProfileController::class, 'create'])
//            ->where('any', '.*');
//    });
});
