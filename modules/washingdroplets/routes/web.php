<?php

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'namespace' => 'SkylarkSoft\GoRMG\Washingdroplets\Controllers'], function(){
    
    // route for washing challan directly from sewingoutput
    Route::get('/sent-directly-sewing-to-washing/{washing_challan_no}', 'WashingChallanController@sendDirectlySewingToWashingChllanWise');
    Route::get('/send-sewing-to-wash/{washing_challan_no}', 'WashingChallanController@sendBundleSewingToWashing');
    Route::post('/sent-washing-factory-post', 'WashingChallanController@sentWashingFactoryPost');

    Route::get('/washing-scan', 'WashingScanController@washingScan');
    Route::post('/washing-scan-post', 'WashingScanController@washingScanPost');
    
    Route::get('/received-bundle-from-wash', 'WashingScanController@receivedBundleFromWashing');
    Route::post('/received-from-washing-post', 'WashingScanController@receivedBundleFromWashingPost');
    Route::get('/close-washing-received-challan/{washing_received_challan_no}', 'WashingScanController@closeWashingReceivedChallan');
    Route::post('/washing-rejection-post', 'WashingScanController@washingRejectionPost');
    //Route::get('/received-from-wash', 'WashingScanController@receivedFromWash');
    Route::post('/received-from-wash-post', 'WashingScanController@receivedFromWashPost');
    Route::get('/sent-washing-factory', 'WashingScanController@sentWashingFactory');
    Route::get('/view-washing-challan', 'WashingChallanController@viewWashingChallan');

    Route::get('/washing-challan-list', 'WashingChallanController@index');
    Route::get('/search-washing-challan', 'WashingChallanController@searchWashingChallan');
    Route::get('/washing-challan/{id}/edit', 'WashingChallanController@edit');
    Route::put('/washing-challan/{id}', 'WashingChallanController@update');

    // Route for report
    Route::get('/order-wise-receievd-from-wash', 'OrderColorSizeReportController@getAllOrderWiseReport');
    Route::get('/order-wise-received-from-wash-download/{type}/{page}', 'OrderColorSizeReportController@getAllOrderWiseReportDownload');
    Route::get('/buyer-wise-receievd-from-wash-view/{buyer_id}', 'OrderColorSizeReportController@getBuyerWiseReportView');
    Route::match(['GET', 'POST'], '/buyer-wise-receievd-from-wash', 'OrderColorSizeReportController@getBuyerWiseReport');

    Route::get('/buyer-wise-receievd-from-wash-download/{type}/{buyer_id}/{page}', 'OrderColorSizeReportController@getBuyerWiseReportDownload');

    Route::match(['GET', 'POST'], '/color-wise-washing-report', 'OrderColorSizeReportController@colorWashingWiseReport');
    Route::get( '/color-wise-washing-report-download/{buyer_id}/{order_id}/{purchase_order_id}/{type}', 'OrderColorSizeReportController@colorWashingWiseReportDownload');
    Route::match(['GET', 'POST'],'/date-wise-washing-report', 'DateWiseReportController@dateWiseWashingReport');
    Route::get('/date-wise-washing-report-download/{type}/{date}', 'DateWiseReportController@dateWiseWashingReportDownload');
    Route::match(['GET', 'POST'],'/month-wise-washing-report', 'DateWiseReportController@monthWiseWashingReport');
    Route::get('/month-wise-washing-report-download/{type}/{from_date}/{to_date}', 'DateWiseReportController@monthWiseWashingReportDownload');

    // Routes for washing received manual
    Route::get('/manual-washing-received-challan-list', 'WashingReceivedManualController@index');
    Route::delete('/manual-washing-received-challan-list/{challan_no}', 'WashingReceivedManualController@destroy');
    Route::get('/received-from-wash', 'WashingReceivedManualController@receivedFromWash');
    Route::get('/get-washing-sent-our-references/{buyer_id}', 'WashingReceivedManualController@getWashingSentOrders');
    Route::get('/get-color-wise-washing-received/{order_id}', 'WashingReceivedManualController@getColorWiseWashingReceived');
    Route::post('/manual-received-from-wash-post', 'WashingReceivedManualController@receivedFromWashPost');
    Route::get('/manual-washing-received-challan-edit/{challan_no}', 'WashingReceivedManualController@washingReceivedChallanEdit');
    Route::post('/manual-washing-received-challan-edit-post', 'WashingReceivedManualController@washingReceivedChallanEditPost');
});