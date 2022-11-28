<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['web', 'auth', 'menu-auth'],
    'namespace' => 'SkylarkSoft\GoRMG\GeneralStore\Controllers',
    'prefix' => 'general-store',
],
    function () {

        Route::resource('/racks', "GsRackController");
        Route::resource('/uom', "GsUomController");

        Route::resource("/items", "GsItemController");
        Route::post("/items/report", "GsItemController@report");
        Route::get('/items/{itemBrand}/delete', 'GsItemController@destroy');

        Route::resource("/items-category", "GsItemCategoryController");
        Route::resource('/brands', "GsBrandController");

        Route::get('/stores/{store}/in', 'StoreController@stockInPage');
        Route::get('/stores/{store}/out_demo', 'StoreController@demoStockOutPage');
        Route::get('/stores/{store}/out', 'StoreController@stockOutPage');
        Route::get('/brand_for_items/{itemId}', 'ItemBrandController@brandsForItem');
        Route::post('/voucher_stock_in', 'VoucherController@saveStockInVoucher');
        Route::post('/voucher_stock_out', 'VoucherController@saveStockOutVoucher')
            ->name("general-store.voucher_stock_out");
        Route::get('/vouchers/{storeId}', 'VoucherController@index')->name("vouchers");
        Route::get('/vouchers/{store}/{id}/{type}', 'VoucherController@voucherEditPage');
        Route::get('/vouchers/{voucher}/delete', 'VoucherController@delete');
        Route::get('/vouchers/{voucher}/view', 'VoucherController@show');
        Route::get('/vouchers/{voucher}/print', 'VoucherController@print');
        Route::get('/vouchers/{voucher}/download', 'VoucherController@downloadVoucher');
        Route::get('/vouchers_transaction/{store}/{voucher}/make_transaction', 'TransactionController@makeTransaction');
        Route::get('/vouchers/{voucher}/download-barcode', 'VoucherController@downloadBarcode');
        Route::get('/barcode_scan', 'BarcodeController@scan');
        Route::get('get_out_rate', 'TransactionController@getOutRate');
        Route::get('get_item_qty', 'TransactionController@getItemQty');

        // Report
        Route::get('/stores/{store}/report', 'ReportController@reportView');
        Route::post('/stores/{store}/report', 'ReportController@report');
        Route::get('/stores/{store}/report2', 'ReportController@categoryWiseSummary');
        Route::get('/stores/{store}/item-wise-summery', 'ReportController@itemWiseSummery');

        // DAILY REPORT
        Route::get('/stores/daily/{store}/report', 'DailyReportController@dailyReportView');
        Route::post('/stores/daily/{store}/report', 'DailyReportController@dailyReport');
    });
