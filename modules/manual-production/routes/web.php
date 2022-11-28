<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'namespace' => 'SkylarkSoft\GoRMG\ManualProduction\Controllers'], function () {

    Route::group(['prefix' => 'manual-production-search', 'namespace' => 'Search'], function () {
        Route::match(['GET', 'POST'], '/selection', 'SearchController@selection');
        Route::match(['GET', 'POST'], '/list', 'SearchController@searchList');
        Route::match(['GET', 'POST'], '/subcontract-factories', 'SearchController@searchSubcontractFactories');
        Route::match(['GET', 'POST'], '/cutting-floors', 'SearchController@searchCuttingFloors');
        Route::match(['GET', 'POST'], '/cutting-tables', 'SearchController@searchCuttingTables');
        Route::match(['GET', 'POST'], '/sewing-floors', 'SearchController@searchSewingFloors');
        Route::match(['GET', 'POST'], '/sewing-lines', 'SearchController@searchSewingLines');
        Route::match(['GET', 'POST'], '/embellishment-floors', 'SearchController@searchEmbellishmentFloors');
        Route::match(['GET', 'POST'], '/finishing-floors', 'SearchController@searchFinishingFloors');
        Route::match(['GET', 'POST'], '/finishing-tables', 'SearchController@searchFinishingTables');
    });

    Route::get('/manual-cutting-production', 'CuttingProductionController@productionEntry');
    Route::post('/manual-cutting-production/store', 'CuttingProductionController@store');

    Route::get('/manual-embellishment-issue', 'EmbellishmentProductionController@issueEntry');
    Route::post('/manual-embellishment-issue/store', 'EmbellishmentProductionController@issueStore');

    Route::get('/manual-embellishment-receive', 'EmbellishmentProductionController@receiveEntry');
    Route::post('/manual-embellishment-receive/store', 'EmbellishmentProductionController@receiveStore');

    Route::get('/manual-sewing-input', 'SewingProductionController@inputEntry');
    Route::post('/manual-sewing-input/store', 'SewingProductionController@inputStore');

    Route::get('/manual-sewing-output', 'SewingProductionController@outputEntry');
    Route::post('/manual-sewing-output/store', 'SewingProductionController@outputStore');

    Route::get('/manual-cutting-delivery-input', 'CuttingDeliveryInputController@index');
    Route::post('/manual-cutting-delivery-input/store', 'CuttingDeliveryInputController@store');

    Route::get('/manual-finishing-iron-production', 'FinishingProductionController@ironProductionEntry');
    Route::post('/manual-finishing-iron-production/store', 'FinishingProductionController@ironProductionStore');

    Route::get('/manual-finishing-poly-packing-production', 'FinishingProductionController@polyPackingProductionEntry');
    Route::post('/manual-finishing-poly-packing-production/store', 'FinishingProductionController@polyPackingProductionStore');

    Route::get('/manual-inspection', 'InspectionController@inspectionEntry');
    Route::post('/manual-inspection/store', 'InspectionController@store');

    Route::get('/manual-shipment', 'ShipmentController@shipmentEntry');
    Route::post('/manual-shipment/store', 'ShipmentController@store');

    Route::group(['namespace' => 'Subcontract', 'prefix' => 'subcontract-factory-profile'], function () {
        Route::get('/', 'FactoryProfileController@index');
        Route::get('/create', 'FactoryProfileController@create');
        Route::post('/', 'FactoryProfileController@store');
        Route::get('/{id}/edit', 'FactoryProfileController@edit');
        Route::post('/{factoryProfile}/status-update', 'FactoryProfileController@statusUpdate');
        Route::put('/{id}', 'FactoryProfileController@update');
        Route::delete('/{id}', 'FactoryProfileController@destroy');
    });

    Route::group(['namespace' => 'Subcontract', 'prefix' => 'subcontract-cutting-floor'], function () {
        Route::get('/', 'CuttingFloorController@index');
        Route::post('/', 'CuttingFloorController@store');
        Route::get('/{id}/edit', 'CuttingFloorController@edit');
        Route::post('/{cuttingFloor}/status-update', 'CuttingFloorController@statusUpdate');
        Route::put('/{id}', 'CuttingFloorController@update');
        Route::delete('/{id}', 'CuttingFloorController@destroy');
    });

    Route::group(['namespace' => 'Subcontract', 'prefix' => 'subcontract-sewing-floor'], function () {
        Route::get('/', 'SewingFloorController@index');
        Route::post('/', 'SewingFloorController@store');
        Route::get('/{id}/edit', 'SewingFloorController@edit');
        Route::post('/{sewingFloor}/status-update', 'SewingFloorController@statusUpdate');
        Route::put('/{id}', 'SewingFloorController@update');
        Route::delete('/{id}', 'SewingFloorController@destroy');
    });

    Route::group(['namespace' => 'Subcontract', 'prefix' => 'subcontract-cutting-table'], function () {
        Route::get('/', 'CuttingTableController@index');
        Route::post('/', 'CuttingTableController@store');
        Route::get('/{id}/edit', 'CuttingTableController@edit');
        Route::post('/{cuttingTable}/status-update', 'CuttingTableController@statusUpdate');
        Route::put('/{id}', 'CuttingTableController@update');
        Route::delete('/{id}', 'CuttingTableController@destroy');
    });

    Route::group(['namespace' => 'Subcontract', 'prefix' => 'subcontract-sewing-line'], function () {
        Route::get('/', 'SewingLineController@index');
        Route::post('/', 'SewingLineController@store');
        Route::get('/{id}/edit', 'SewingLineController@edit');
        Route::post('/{sewingLine}/status-update', 'SewingLineController@statusUpdate');
        Route::put('/{id}', 'SewingLineController@update');
        Route::delete('/{id}', 'SewingLineController@destroy');
    });

    Route::group(['namespace' => 'Subcontract', 'prefix' => 'subcontract-embellishment-floor'], function () {
        Route::get('/', 'EmbellishmentFloorController@index');
        Route::post('/', 'EmbellishmentFloorController@store');
        Route::get('/{id}/edit', 'EmbellishmentFloorController@edit');
        Route::post('/{embellishmentFloor}/status-update', 'EmbellishmentFloorController@statusUpdate');
        Route::put('/{id}', 'EmbellishmentFloorController@update');
        Route::delete('/{id}', 'EmbellishmentFloorController@destroy');
    });

    Route::group(['prefix' => 'manual-product/common-api'], function () {
        Route::get('/garment-production-entry-variable', 'CommonController@getGarmentProductionVariable');
        Route::get('/get-companies', 'CommonController@getCompanies');
        Route::get('/get-buyers/{id}', 'CommonController@getBuyers');
        Route::get('/get-subcontract-companies', 'CommonController@subcontractCompanies');
        Route::get('/get-buyers-orders', 'CommonController@getBuyersOrders');
        Route::get('/get-orders-colors', 'CommonController@getOrdersColors');
        Route::get('/get-buyers-data', 'CommonController@getBuyerData');
        Route::get('/get-floors', 'CommonController@getFloors');
    });

    Route::group(['namespace' => 'Subcontract', 'prefix' => 'subcontract-finishing-floor'], function () {
        Route::get('/', 'FinishingFloorController@index');
        Route::post('/', 'FinishingFloorController@store');
        Route::get('/{id}/edit', 'FinishingFloorController@edit');
        Route::post('/{finishingFloor}/status-update', 'FinishingFloorController@statusUpdate');
        Route::put('/{id}', 'FinishingFloorController@update');
        Route::delete('/{id}', 'FinishingFloorController@destroy');
    });

    Route::group(['namespace' => 'Subcontract', 'prefix' => 'subcontract-finishing-table'], function () {
        Route::get('/', 'FinishingTableController@index');
        Route::post('/', 'FinishingTableController@store');
        Route::get('/{id}/edit', 'FinishingTableController@edit');
        Route::post('/{finishingTable}/status-update', 'FinishingTableController@statusUpdate');
        Route::put('/{id}', 'FinishingTableController@update');
        Route::delete('/{id}', 'FinishingTableController@destroy');
    });

    Route::group(['namespace' => 'Reports'], function () {
        Route::get('manual-date-wise-cutting-report', 'CuttingReportController@dateWiseCuttingProductionReport');
        Route::get('manual-date-wise-cutting-report/pdf', 'CuttingReportController@dateWiseCuttingProductionReportPdf');
        Route::get('manual-date-wise-cutting-report/excel', 'CuttingReportController@dateWiseCuttingProductionReportExcel');
        Route::match(['GET', 'POST'], 'manual-style-overall-summary-report', 'CommonReportController@styleOverallSummaryReport');
        Route::post('manual-style-overall-summary-report/pdf', 'CommonReportController@styleOverallSummaryReportPdf');
        Route::post('manual-style-overall-summary-report/excel', 'CommonReportController@styleOverallSummaryReportExcel');
        Route::get('manual-date-wise-print-embr-report', 'PrintEmbrReportController@dateWisePrintEmbrReport');

        Route::get('manual-challan-wise-embr-report', 'PrintEmbrReportController@challanWiseEmbrReport');
        Route::get('manual-challan-wise-embr-report/pdf', 'PrintEmbrReportController@challanWiseEmbrReportPdf');
        Route::get('manual-challan-wise-embr-report/excel', 'PrintEmbrReportController@challanWiseEmbrReportExcel');

        Route::get('manual-challan-wise-print-report', 'PrintEmbrReportController@challanWisePrintReport');
        Route::get('manual-challan-wise-print-report/pdf', 'PrintEmbrReportController@challanWisePrintReportPdf');
        Route::get('manual-challan-wise-print-report/excel', 'PrintEmbrReportController@challanWisePrintReportExcel');

        Route::get('manual-date-wise-print-embr-report-pdf', 'PrintEmbrReportController@dateWisePrintEmbrReportPdf');
        Route::get('manual-date-wise-print-embr-report-excel', 'PrintEmbrReportController@dateWisePrintEmbrReportExcel');
        Route::get('floor-wise-style-in-out-summary', 'FloorWiseStyleInOutSummaryController@index');
        Route::get('floor-wise-style-in-out-summary-pdf', 'FloorWiseStyleInOutSummaryController@pdf');
        Route::get('floor-wise-style-in-out-summary-excel', 'FloorWiseStyleInOutSummaryController@excel');

        Route::get('manual-daily-input-unit-wise-report', 'SewingReportController@dailyInputUnitWiseReport');
        Route::get('manual-daily-input-unit-wise-report/pdf', 'SewingReportController@dailyInputUnitWiseReportPdf');
        Route::get('manual-daily-input-unit-wise-report/excel', 'SewingReportController@dailyInputUnitWiseReportExcel');

        Route::get('manual-challan-wise-style-input-summary', 'CommonReportController@challanWiseStyleInputSummary');
        Route::get('manual-challan-wise-style-input-summary/pdf', 'CommonReportController@challanWiseStyleInputSummaryPdf');
        Route::get('manual-challan-wise-style-input-summary/excel', 'CommonReportController@challanWiseStyleInputSummaryExcel');

        Route::get('manual-floor-size-wise-style-in-out-summary', 'SewingReportController@floorSizeWiseStyleInOutSummary');
        Route::get('manual-floor-size-wise-style-in-out-summary/pdf', 'SewingReportController@floorSizeWiseStyleInOutSummaryPdf');
        Route::get('manual-floor-size-wise-style-in-out-summary/excel', 'SewingReportController@floorSizeWiseStyleInOutSummaryExcel');

        Route::get('manual-date-floor-wise-hourly-sewing-output', 'SewingReportController@dateFloorWiseHourlySewingOutput');
        Route::get('manual-date-floor-wise-hourly-sewing-output/pdf', 'SewingReportController@dateFloorWiseHourlySewingOutputPdf');
        Route::get('manual-date-floor-wise-hourly-sewing-output/excel', 'SewingReportController@dateFloorWiseHourlySewingOutputExcel');

        Route::get('daily-sewing-production-report', 'DailySewingProductionReportController@index');
        Route::get('daily-sewing-production-report-pdf', 'DailySewingProductionReportController@pdf');
        Route::get('daily-sewing-production-report-excel', 'DailySewingProductionReportController@excel');

        Route::get('buyer-style-color-wise-daily-sewing-output-report', 'BuyerStyleColorWiseDailySewingOutputReportController@index');
        Route::get('buyer-style-color-wise-daily-sewing-output-report-pdf', 'BuyerStyleColorWiseDailySewingOutputReportController@pdf');
        Route::get('buyer-style-color-wise-daily-sewing-output-report-excel', 'BuyerStyleColorWiseDailySewingOutputReportController@excel');

        Route::get('style-wise-rejection-report', 'CommonReportController@styleWiseRejectionReport');
        Route::get('style-wise-rejection-report/pdf', 'CommonReportController@styleWiseRejectionReportPdf');
        Route::get('style-wise-rejection-report/excel', 'CommonReportController@styleWiseRejectionReportExcel');

        Route::get('yearly-rejection-summary-report', 'CommonReportController@yearlyRejectionReport');
        Route::get('yearly-rejection-summary-report/pdf', 'CommonReportController@yearlyRejectionReportPdf');
        Route::get('yearly-rejection-summary-report/excel', 'CommonReportController@yearlyRejectionReportExcel');
    });

});
