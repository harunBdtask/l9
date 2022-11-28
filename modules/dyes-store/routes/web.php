<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\DyesStore\Controllers\DyesAndChemicalIssueReturnController;
use SkylarkSoft\GoRMG\DyesStore\Controllers\DyesAndChemicalReceiveReportController;
use SkylarkSoft\GoRMG\DyesStore\Controllers\DyesAndChemicalReceiveReturnController;
use SkylarkSoft\GoRMG\DyesStore\Controllers\DyesAndChemicalsStockSummaryReportController;

Route::group([
    'middleware' => ['web', 'auth', 'menu-auth'],
    'namespace' => 'SkylarkSoft\GoRMG\DyesStore\Controllers',
    'prefix' => 'dyes-store'
], function () {

    Route::resource('/racks', "DsRackController");
    Route::resource('/uom', "DsUomController");

    Route::resource('/stores', "DsStoreController",[
        'names' => [
            'index' => 'stores.index',
            'store' => 'stores.store',
            'update' => 'stores.update',
            'edit' => 'stores.edit',
            'destroy' => 'stores.destroy',
        ],
    ]);

    Route::resource('/department', "DsDepartmentController",[
        'names' => [
            'index' => 'department.index',
            'store' => 'department.store',
            'update' => 'department.update',
            'edit' => 'department.edit',
            'destroy' => 'department.destroy',
        ],
    ]);

    Route::resource('/storage-location', "DsStorageLocationController");

    Route::resource('/customers', 'DsCustomerController');

    Route::resource("/items", "DsItemController");
    Route::get("/items/edit/{id}", "DsItemController@edit");
    Route::post("/items/report", "DsItemController@report");
    Route::get('/items/{itemBrand}/delete', 'DsItemController@destroy');

    Route::resource("/items-category", "DsItemCategoryController");
    Route::resource('/brands', "DsBrandController");

    // Receive router...
    Route::resource('/dyes-chemical', 'DyesAndChemicalReceiveController');
    Route::get('/dyes-chemical/{id}/destroy', 'DyesAndChemicalReceiveController@destroy');

    //receive return

    Route::group(['prefix' => '/dyes-chemical-receive-return'],function() {
        Route::get('', [DyesAndChemicalReceiveReturnController::class, 'index']);
        Route::post('', [DyesAndChemicalReceiveReturnController::class, 'store']);
        Route::get('/{receiveReturn}/edit', [DyesAndChemicalReceiveReturnController::class, 'edit']);
        Route::put('/{receiveReturn}', [DyesAndChemicalReceiveReturnController::class, 'update']);
        Route::delete('/{receiveReturn}', [DyesAndChemicalReceiveReturnController::class, 'delete']);

        Route::get('{receiveReturn}/stock-transaction', [DyesAndChemicalReceiveReturnController::class, 'makeTransaction']);

        Route::get('/{any?}', [DyesAndChemicalReceiveReturnController::class, 'create'])->where('any', '.*');
    });

    //Issue Return

    Route::group(['prefix' => '/dyes-chemical-issue-return'], function(){
        Route::get('', [DyesAndChemicalIssueReturnController::class, 'index']);
        Route::post('', [DyesAndChemicalIssueReturnController::class, 'store']);
        Route::get('/{chemicalIssueReturn}/edit', [DyesAndChemicalIssueReturnController::class, 'edit']);
        Route::put('/{chemicalIssueReturn}', [DyesAndChemicalIssueReturnController::class, 'update']);

        Route::get('{chemicalIssueReturn}/stock-transaction', [DyesAndChemicalIssueReturnController::class, 'makeTransaction']);

        Route::get('/{any?}', [DyesAndChemicalIssueReturnController::class, 'create'])->where('any', '.*');
    });

    // Issues Router...
    Route::resource('/dyes-chemical-issue', 'DyesAndChemicalIssueController');
    Route::get('/dyes-chemical-issue/{id}/destroy', 'DyesAndChemicalIssueController@destroy');

    // Transaction Router...
    Route::get('/dyes-chemical/{id}/stock-transaction', 'DyesAndChemicalTransactionController@makeStockTransaction');

    // Stock Summery Report Router...
    Route::get('dyes-stock-summary-report', 'DyesAndChemicalsStockSummaryReportController@reportView');
    Route::get('dyes-stock-summary-report-two', 'DyesAndChemicalsStockSummaryReportTwoController@reportData');
    Route::get('dyes-stock-summary-report/daily', [DyesAndChemicalsStockSummaryReportController::class, 'dailyReport']);
    Route::get('dyes-stock-summary-report/daily/pdf', [DyesAndChemicalsStockSummaryReportController::class, 'dailyReportPdf']);
    Route::get('dyes-stock-summary-report/daily/excel', [DyesAndChemicalsStockSummaryReportController::class, 'dailyReportExcel']);

    Route::group(['prefix' => 'dyes-and-chemical-receive-report'], function () {
        Route::get('/', [DyesAndChemicalReceiveReportController::class, 'index']);
        Route::get('/get-report',[DyesAndChemicalReceiveReportController::class, 'getReport']);
        Route::get('/pdf', [DyesAndChemicalReceiveReportController::class, 'pdf']);
        Route::get('/excel', [DyesAndChemicalReceiveReportController::class, 'excel']);
    });

    // Dyes Chemicals transfer route...
    Route::resource('/dyes-chemical-transfer', 'DyesAndChemicalsTransferController');
    Route::get('/dyes-chemical-transfer/{id}/destroy', 'DyesAndChemicalsTransferController@destroy');

    // Dyes Chemicals Generate Barcode...
    Route::get('/dyes-chemicals-download-barcode/{id}', 'DyesAndChemicalReceiveController@downloadBarcodes');

    // Dyes Api...
    Route::get('/item-brand-get/{itemId}', 'DyesItemWiseBrandApiController');
    Route::get('/item-category-get/{itemId}', 'DyesItemWiseCategoryApiController');
    Route::get('/get-dyes-item-receive-details/{item_id}/{deliveryDate}/{storeId}/{lifeEndDays}', 'DyesChemicalsItemReceiveDetailsApiController');
    Route::get('/get-dyes-transaction-items/{storeId}', 'DyesTransactionItemsApiController');
    Route::get('/dyes-item-wise-life-end-days/{itemId}', 'DyesAndChemicalsItemWiseLifeEndDaysApiController');
    Route::get('/dyes-barcode-details', 'DyesAndChemicalsBarcodeDetailsApiController@scan');
    Route::get('/get-receive-nos', 'DyesAndChemicalReceiveController@getReceiveNos');

    Route::group(['namespace' => 'Api'], function () {
        Route::get('/get-suppliers', 'SuppliersApiController');
        Route::get('/get-dyes-items', 'DyesItemsApiController');
        Route::get('/item-uom-get/{itemId}', 'ItemWiseUomApiController');
        Route::get('/get-customers', 'CustomersApiController');
        Route::get('/get-department', 'DepartmentApiController');
        Route::get('/storage-locations', 'StorageLocationApiController');
        Route::get('/get-sub-stores/{storeId}', 'SubStoreApiController');
        Route::get('get-requisitions', 'RequisitionsController');
        Route::get('get-recipe-details/{dyeingRecipe}', 'RequisitionWiseRecipeDetailsController');
        Route::get('/get-dyes-chemical-receives/{challan_no}', 'DyesChemicalReceiveApiController');
        Route::get('/get-dyes-chemical-issues', 'DyesChemicalIssueApiController');
        Route::get('/get-dyes-chemical-system-generate-wise-challan', 'DyesChemicalSystemGenerateWiseChallanApiController');
        Route::get('/dyes_chemical_receive_detail', 'DyesChemicalReceiveDetailApiController');
        Route::get('/dyes_chemical_issue_detail', 'DyesChemicalIssueDetailApiController');
        Route::get('/fetch-system-generate-wise-supplier/{dyesChemicalsReceive}', 'fetchGenerateIdWiseSupplierApiController');
    });
});
