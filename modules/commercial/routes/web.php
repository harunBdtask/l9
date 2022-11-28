<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Commercial\Controllers\ActualCostEntry\ActualCostEntryController;
use SkylarkSoft\GoRMG\Commercial\Controllers\BTBLcAmendment\BtbLcAmendmentController;
use SkylarkSoft\GoRMG\Commercial\Controllers\BTBMarginLC\B2BMarginLCPadPreviewController;
use SkylarkSoft\GoRMG\Commercial\Controllers\CommercialRealization\CommercialRealizationController;
use SkylarkSoft\GoRMG\Commercial\Controllers\CommercialRealization\CommercialRealizationInvoiceController;
use SkylarkSoft\GoRMG\Commercial\Controllers\DocumentSubmissionController;
use SkylarkSoft\GoRMG\Commercial\Controllers\ExportInvoiceController;
use SkylarkSoft\GoRMG\Commercial\Controllers\ImportDocumentAcceptanceController;
use SkylarkSoft\GoRMG\Commercial\Controllers\ImportLCChargesEntry\ImportLCChargesEntryController;
use SkylarkSoft\GoRMG\Commercial\Controllers\Imports\B2BMarginLCAmendmentController as B2BMarginLCAmendmentAPIController;
use SkylarkSoft\GoRMG\Commercial\Controllers\Imports\ImportDocumentAcceptanceController as ImportDocumentAcceptanceAPIController;
use SkylarkSoft\GoRMG\Commercial\Controllers\Imports\ImportDocumentPIInfoController as ImportDocumentPIInfoAPIController;
use SkylarkSoft\GoRMG\Commercial\Controllers\Imports\ImportDocumentShippingInfoController as ImportDocumentShippingInfoAPIController;
use SkylarkSoft\GoRMG\Commercial\Controllers\lien\AdvisingBankSearchController;
use SkylarkSoft\GoRMG\Commercial\Controllers\lien\LienController;
use SkylarkSoft\GoRMG\Commercial\Controllers\PreExportFinance\PreExportFinanceController;
use SkylarkSoft\GoRMG\Commercial\Controllers\PrimaryContractBTBStatusReportController;
use SkylarkSoft\GoRMG\Commercial\Controllers\PrimaryMasterContract\PrimaryMasterContractController;
use SkylarkSoft\GoRMG\Commercial\Controllers\PrimaryMasterContractAmendment\PrimaryMasterContractAmendmentController;
use SkylarkSoft\GoRMG\Commercial\Controllers\CommercialReportController;
use SkylarkSoft\GoRMG\Commercial\Controllers\SalesContractPadPreviewController;
use SkylarkSoft\GoRMG\Commercial\Controllers\PerformanceReportController;
use SkylarkSoft\GoRMG\Commercial\Controllers\PITrackingReportController;

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial', 'namespace' => 'SkylarkSoft\GoRMG\Commercial\Controllers'], function () {
    Route::get('buyerIdToJson', 'SalesContractController@buyerIdToJson');
    Route::get('sales-contract/entry', 'SalesContractController@create');
    Route::post('sales-contract', 'SalesContractController@store');
    Route::get('sales-contract/{contract}', 'SalesContractController@view');
    Route::get('sales-contract/{contract}/pdf', 'SalesContractController@pdf');
    Route::get('sales-contract/{contract}/edit', 'SalesContractController@edit')->name('sales.contract.edit');
    Route::get('sales-contracts', 'SalesContractController@index');
    Route::post('sales-contracts-details/{contract}', 'SalesContractController@storeDetails');
    Route::put('sales-contract/{contract}', 'SalesContractController@update');
    Route::get('sales-contracts-details/{contract}', 'SalesContractController@fetchDetails');
    Route::delete('sales-contract-details/{detail}', 'SalesContractController@deleteDetail');
    Route::get('sales-contract-search', 'SalesContractController@search');
    Route::post('sales-contract/{contract}', 'SalesContractController@destroy');
    Route::delete('sales-contract/{contract}', 'SalesContractController@destroy');

    Route::get('sales-contract-amendments', 'SalesContractAmendmentController@index');
    Route::get('sales-contract-amendments/create', 'SalesContractAmendmentController@fileNoForm');
    Route::post('sales-contract-amendments/create-form', 'SalesContractAmendmentController@createForm');
    Route::delete('sales-contract-amendments/{contract}', 'SalesContractAmendmentController@destroy');

    Route::get('sales-contract/{contract}/pad-preview', [SalesContractPadPreviewController::class, 'index']);
    Route::get('sales-contract/{contract}/pad-preview/pdf', [SalesContractPadPreviewController::class, 'getPdf']);

    Route::post('sc-amendment/{contract}', 'SalesContractAmendmentController@store');

    Route::get('proforma-invoice', 'ProformaInvoiceController@index');
    Route::get('proforma-invoice/{invoice}/view', 'ProformaInvoiceController@view');
    Route::get('proforma-invoice/{invoice}/pdf', 'ProformaInvoiceController@pdf');
    Route::get('proforma-invoice/{invoice}/file_view', 'ProformaInvoiceController@file_view');

    /*PROFORMA INVOICE*/
    Route::get('pi-list', 'ProformaInvoiceController@index');
    Route::get('pi/{invoice}', 'ProformaInvoiceController@show');
    Route::get('proforma-invoice/create', 'ProformaInvoiceController@create');
    Route::get('proforma-invoice/{invoice}/edit', 'ProformaInvoiceController@edit');
    Route::post('proforma-invoice', 'ProformaInvoiceController@store');
    Route::put('proforma-invoice/{invoice}', 'ProformaInvoiceController@update');
    Route::get('pi-list/search', 'ProformaInvoiceController@search');
    Route::put('proforma-invoice-details/{invoice}', 'ProformaInvoiceController@saveDetails');
    Route::put('proforma-invoice-delete/{invoice}', 'ProformaInvoiceController@deleteDetails');
    Route::get('pi-embellishment/{invoice}', 'ProformaInvoiceController@embellishmentDetails');
    Route::get('fetch-trims-booking-details', 'ProformaInvoiceController@fetchTrimsBookingsDate');
    Route::get('fetch-fabric-booking-details', 'ProformaInvoiceController@fetchFabricBookingsDate');
    Route::get('fetch-yarn-booking-details', 'ProformaInvoiceController@fetchYarnBookingsDate');
    Route::put('fabric-trims-pi-details/{invoice}', 'ProformaInvoiceController@saveFabricTrimsPIDetails');
    Route::put('fabric-trims-pi-delete/{invoice}', 'ProformaInvoiceController@deleteFabricTrimsDetails');
    Route::put('save-yarn-pi-details/{invoice}', 'ProformaInvoiceController@saveYarnPIDetails');
    Route::get('fetch-embellishment_work_order_details', 'ProformaInvoiceController@fetchEmbellishmentData');
//    Lien Section
    Route::group(['prefix' => 'lien'], function () {
        Route::get('', [LienController::class, 'index']);
        Route::get('{id}/pdf', [LienController::class, 'pdf']);
        Route::get('{id}/view', [LienController::class, 'view']);
        Route::get('{id}/excel', [LienController::class, 'excel']);
        Route::delete('{id}/delete', [LienController::class, 'destroy']);
        Route::get('{any?}', [LienController::class, 'create'])->where('any', '.*');
    });
});

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'namespace' => 'SkylarkSoft\GoRMG\Commercial\Controllers'], function () {
    Route::get('orders-for-sales-contract/{buyer}', 'SalesContractController@ordersForSalesContract');
    Route::get('purchase-orders-for-sales-contract/{order}', 'SalesContractController@purchaseOrdersForOrder');
    Route::get('sales-contract-details/{type}/{id}', 'SalesContractController@salesContractDetailsSelection');
    Route::get('get-sales-detail-form-create', 'SalesContractController@generateDetailForm');

    Route::get('fetch-importers', 'FormsController@importers');
    Route::get('fetch-item-categories', 'FormsController@itemCategories');
    Route::get('fetch-item-groups', 'FormsController@itemGroups');
    Route::get('fetch-suppliers', 'FormsController@suppliers');
    Route::get('approval-users', 'FormsController@approveUsers');
    Route::get('fetch-yarn-counts', 'FormsController@yarnCounts');
    Route::get('fetch-yarn-compositions', 'FormsController@yarnCompositions');
    Route::get('fetch-unit-of-measurements', 'FormsController@unitsOfMeasurements');
    Route::get('fetch-yarn-types', 'FormsController@yarnTypes');
    Route::get('fetch-garments-types', 'FormsController@garmentsType');
    Route::get('fetch-embellishment-names', 'FormsController@embellishmentNames');
    Route::get('fetch-embellishment-types/{embellishmentItem}', 'FormsController@embellishmentTypes');
    Route::get('fetch-fabric-compositions', 'FormsController@fabricCompositions');
    Route::get('fetch-fabric-compositions/filter', 'FormsController@filterFabricCompositions');
    Route::get('fetch-item-creations', 'FormsController@fetchItemCreations');
    Route::get('fetch-item-creations/filter', 'FormsController@filterItemCreations');

    Route::get('export-lc-details/{type}/{id}', 'ExportLCController@exportContractDetailsSelection');
    Route::get('get-export-detail-form-create', 'ExportLCController@generateDetailForm');
});

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial', 'namespace' => 'SkylarkSoft\GoRMG\Commercial\Controllers'], function () {
    Route::get('export-lc/entry', 'ExportLCController@create');
    Route::post('export-lc', 'ExportLCController@store');
    Route::get('export-lc/{contract}', 'ExportLCController@view');
    Route::get('export-lc/{contract}/pdf', 'ExportLCController@pdf');
    Route::get('export-lc/{contract}/edit', 'ExportLCController@edit')->name('export.contract.edit');
    Route::put('export-lc/{contract}', 'ExportLCController@update');
    Route::get('export-lc', 'ExportLCController@index');
    Route::get('/export-lc-search', 'ExportLCController@search');
    Route::post('export-lc/{contract}', 'ExportLCController@destroy');
    Route::get('test', 'ExportLCController@test');
    Route::post('export-lc-details/{contract}', 'ExportLCController@storeDetails');
    Route::get('export-lc-details/{contract}', 'ExportLCController@fetchDetails');
    Route::delete('export-lc-details/{detail}', 'ExportLCController@deleteDetail');

    Route::get('export-lc-amendments', 'ExportLCAmendmentController@index');
    Route::get('export-lc-amendments/create', 'ExportLCAmendmentController@fileNoForm');
    Route::post('export-lc-amendments/form-create', 'ExportLCAmendmentController@createForm');
    Route::post('export-amendment/{contract}', 'ExportLCAmendmentController@store');
});

// For Commercial Realization
Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial/realizations'], function () {
    Route::get('/', [CommercialRealizationController::class, 'index']);
    Route::get('/create', [CommercialRealizationController::class, 'create']);
    Route::post('/store', [CommercialRealizationController::class, 'store']);
    Route::get('/{commercialRealization}/edit', [CommercialRealizationController::class, 'edit']);
    Route::put('/{commercialRealization}', [CommercialRealizationController::class, 'update']);
    Route::delete('/{commercialRealization}', [CommercialRealizationController::class, 'destroy']);
    Route::get('/{commercialRealization}/show', [CommercialRealizationController::class, 'show']);
    Route::get('/invoice/create/{documentSubmission}', [CommercialRealizationInvoiceController::class, 'create']);
    Route::delete('/invoice/{commercialRealizationInvoice}', [CommercialRealizationInvoiceController::class, 'destroy']);
});

// for export proceeds realization
// These urls will be obsolete this will change to commercial/realizations
Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial', 'namespace' => 'SkylarkSoft\GoRMG\Commercial\Controllers\ExportProceedRealizations'], function () {
    Route::get('export-proceed-realizations/create', 'ExportProceedsRealizationController@create');
    Route::get('export-proceed-realizations/{id}/edit', 'ExportProceedsRealizationController@create');
    Route::get('export-proceed-realizations', 'ExportProceedsRealizationController@index');
});

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial', 'namespace' => 'SkylarkSoft\GoRMG\Commercial\Controllers'], function () {
    Route::get('commercial-variable/create', 'CommercialVariableController@create');
    Route::post('commercial-variable', 'CommercialVariableController@store');
    Route::get('fetch-btb-percent-value', 'CommercialVariableController@fetchBtbPercent');
    Route::get('variable-settings-form', 'CommercialVariableController@generateVariableSettingsForm');
});

Route::group([
    'middleware' => ['web', 'auth', 'menu-auth'],
    'prefix' => 'commercial-api/v1',
    'namespace' => 'SkylarkSoft\GoRMG\Commercial\Controllers'], function () {
    // Commercial Realization
    Route::get('/fetch-commercial-realization-data', [CommercialRealizationController::class, 'fetchData']);

    // lien section
    Route::get('advising-bank-list', [AdvisingBankSearchController::class, '__invoke']);
    Route::group(['prefix' => 'lien'], function () {
        Route::post('', [LienController::class, 'store']);
        Route::get('{id}/edit', [LienController::class, 'edit']);
        Route::post('{id}/store', [LienController::class, 'storeDetails']);
    });
    // end lien section
    Route::get('trims-work-orders/{factoryId}/{buyerId}', 'CommercialCommonAPI@fetchWorkWorkOrdersTrims');
    Route::get('yarn-work-orders/{factoryId}/{buyerId}', 'CommercialCommonAPI@fetchWorkWorkOrdersYarn');
    Route::get('fabric-work-orders/{factoryId}/{buyerId}', 'CommercialCommonAPI@fetchWorkWorkOrdersFabrics');
    Route::get('get-currencies', 'CommercialCommonAPI@getCurrencies');
    Route::get('get-buying-agents', 'CommercialCommonAPI@getBuyingAgent');
    Route::get('get-factories', 'CommercialCommonAPI@getFactories');
    Route::get('get-dyes-store-items', 'CommercialCommonAPI@getDyesStoreItems');
    Route::get('/{factoryId}/get-btb-limit', 'CommercialCommonAPI@getBtbLimit');
    Route::get('lien-banks-options', 'CommercialCommonAPI@lienBanks');
    Route::get('countries-options', 'CommercialCommonAPI@countries');
    Route::get('account-head-options', 'CommercialCommonAPI@accountHeads');
    Route::get('inco-term-options', 'CommercialCommonAPI@incoTerms');
    Route::get('retire-source-search', 'Imports\ImportDocumentAcceptanceController@retireSource');
    Route::get('acceptance-time-search', 'Imports\ImportDocumentAcceptanceController@AcceptanceTime');

    Route::group(['prefix' => 'export-invoices'], function () {
        Route::get('lc-sc-search', 'ExportInvoices\LCOrSCSearchController');
        Route::get('po-color-sizes-details', 'ExportInvoices\ExportInvoiceDetailController@poDetails');
        Route::get('', 'ExportInvoiceController@index');
        Route::get('{invoice}', 'ExportInvoiceController@show');
        Route::post('', 'ExportInvoiceController@store');
        Route::post('new-store', 'ExportInvoiceController@newStore');
        Route::put('{invoice}/additional-info', 'ExportInvoiceController@storeAdditionalInfo');
        Route::put('{invoice}/shipping-info', 'ExportInvoiceController@storeShippingInfo');
        Route::put('{invoice}', 'ExportInvoiceController@update');
        Route::delete('{invoice}', 'ExportInvoiceController@delete');
        Route::get('{invoice}/details', 'ExportInvoices\ExportInvoiceDetailController@index');
        Route::post('{invoice}/details', 'ExportInvoices\ExportInvoiceDetailController@store');
        Route::delete('{detail}/details', 'ExportInvoices\ExportInvoiceDetailController@delete');
    });

    // These urls will be obsolete this will change to commercial/realizations
    Route::group(['prefix' => 'export-proceed-realizations', 'namespace' => 'ExportProceedRealizations'], function () {
        Route::get('export-invoice-search', 'ExportProceedsRealizationInvoiceSearchController');
        Route::get('realization/list', 'ExportProceedsRealizationController@index');
        Route::post('realization/save', 'ExportProceedsRealizationController@store');
        Route::get('{realization}/edit', 'ExportProceedsRealizationController@edit');
        Route::put('{realization}/update', 'ExportProceedsRealizationController@update');
        Route::get('{realization}/show', 'ExportProceedsRealizationController@show');
        Route::delete('{id}', 'ExportProceedsRealizationController@delete');
        Route::delete('detail/{id}', 'ExportProceedsRealizationController@deleteDetail');
    });

    Route::group(['prefix' => 'document-submissions'], function () {
        Route::post('lc-search', 'DocumentSubmissionController@lcSearch');
        Route::post('', 'DocumentSubmissionController@store');
        Route::get('/get-bank-refs', 'DocumentSubmissionController@getBankRefs');
        Route::get('{documentSubmission}', 'DocumentSubmissionController@show');
        Route::put('{documentSubmission}', 'DocumentSubmissionController@update');
        Route::delete('{documentSubmission}', 'DocumentSubmissionController@delete');
        Route::delete('transaction/{documentTransaction}', 'DocumentSubmissionController@deleteTransaction');
    });

    Route::group(['prefix' => 'import-lc-charges', 'namespace' => 'Imports'], function () {
        Route::post('', 'ImportLCChargeController@store');
        Route::post('lc-search', 'ImportLCChargeController@letterOfCreditsSearch');
        Route::get('list/{b2bMarginLcId}', 'ImportLCChargeController@index');
        Route::delete('{importLcCharge}', 'ImportLCChargeController@delete');
    });

    Route::group(['prefix' => 'b2b-margin-lc', 'namespace' => 'BTBMarginLC'], function () {
        // Search For LC/SC
        Route::get('lc-sc-search', 'LcScSearchController@search');

        Route::get('proforma-invoice-search', 'B2BMarginLCController@proformaInvoiceSearch');
        Route::get('{b2BMarginLC}', 'B2BMarginLCController@show');
        Route::post('store', 'B2BMarginLCController@store');
        Route::get('details/{b2BMarginLC}', 'B2BMarginLCController@getDetails');
        Route::put('details/{b2BMarginLC}', 'B2BMarginLCController@details');
        Route::delete('details/{b2BMarginLCDetail}', 'B2BMarginLCController@detailsDelete');
        Route::put('{b2BMarginLC}', 'B2BMarginLCController@update');
    });

    Route::group(['prefix' => 'actual-cost-entry', 'namespace' => 'ActualCostEntry'], function () {
        Route::post('', [ActualCostEntryController::class, 'store']);
        Route::get('selected-options', [ActualCostEntryController::class, 'selectedOptions']);
        Route::get('{actualCost}', [ActualCostEntryController::class, 'show']);
        Route::put('{actualCost}', [ActualCostEntryController::class, 'update'])->where('actualCost', '[0-9]+');
    });

    Route::group(['prefix' => 'import-documents'], function () {
        /*------------------------------------------------------------------
        * B2B LC Search
        * ------------------------------------------------------------------*/
        Route::get('b2b-lc-search', 'Imports\ImportDocumentB2BLCSearchController');
        Route::get('cumulative-acceptance-amount-histories', [ImportDocumentPIInfoAPIController::class, 'piHistories']);


        /*------------------------------------------------------------------
        * Import Document Acceptance
        * ------------------------------------------------------------------*/
        Route::post('', [ImportDocumentAcceptanceAPIController::class, 'store']);
        Route::get('{importDocument}', [ImportDocumentAcceptanceAPIController::class, 'show']);
        Route::put('{importDocument}', [ImportDocumentAcceptanceAPIController::class, 'update']);
        Route::delete('{importDocument}', [ImportDocumentAcceptanceAPIController::class, 'delete']);

        /*------------------------------------------------------------------
        * Import Document Shipping Info
        * ------------------------------------------------------------------*/
        Route::match(['POST', 'PUT'], '{importDocument}/shipping-info', [ImportDocumentShippingInfoAPIController::class, 'store']);
        Route::get('{documentShippingInfo}/shipping-info', [ImportDocumentShippingInfoAPIController::class, 'show']);
        Route::delete('{documentShippingInfo}/shipping-info', [ImportDocumentShippingInfoAPIController::class, 'delete']);

        /*------------------------------------------------------------------
        * Import Document PI INFO
        * ------------------------------------------------------------------*/
        Route::get('{importDocument}/pi-infos', [ImportDocumentPIInfoAPIController::class, 'index']);
        Route::get('{documentShippingInfo}/pi-info', [ImportDocumentPIInfoAPIController::class, 'show']);
        Route::delete('{documentShippingInfo}/pi-info', [ImportDocumentPIInfoAPIController::class, 'delete']);
    });


    Route::group(['prefix' => 'b2b-margin-lc-amendments'], function () {
        Route::get('b2b-margin-lc-search', [B2BMarginLCAmendmentAPIController::class, 'b2bMarginLCSearch']);
        Route::put('{b2BMarginLC}', [B2BMarginLCAmendmentAPIController::class, 'store'])->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'import-payments', 'namespace' => 'ImportPayment'], function () {
        Route::get('/search-documents', 'ImportPaymentController@searchImportDocuments');
        Route::get('/load-common-data', 'ImportPaymentController@loadCommonData');
        Route::post('/', 'ImportPaymentController@store');
        Route::get('/get/{id}', 'ImportPaymentController@get');
    });

    Route::get('/proforma-invoice/fetch-item-pi', 'CommercialCommonAPI@fetchItemPIList');
});


Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial/btb-margin-lc', 'namespace' => 'SkylarkSoft\GoRMG\Commercial\Controllers\BTBMarginLC'], function () {
    Route::get('', 'B2BMarginLCController@index');
    Route::get('/create', 'B2BMarginLCController@create');
    Route::get('/{id}/view', 'B2BMarginLCController@view');
    Route::delete('{b2BMarginLC}', 'B2BMarginLCController@delete');
    Route::get('/{b2BMarginLC}/pad-preview', [B2BMarginLCPadPreviewController::class, 'index']);
    Route::get('/{b2BMarginLC}/pad-preview/pdf', [B2BMarginLCPadPreviewController::class, 'getPdf']);
});

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial/document-submission', 'namespace' => 'SkylarkSoft\GoRMG\Commercial\Controllers\DocumentSubmission'], function () {
    Route::get('', [DocumentSubmissionController::class, 'index']);
    Route::get('/create', [DocumentSubmissionController::class, 'create']);
    Route::get('{documentSubmission}/view', [DocumentSubmissionController::class, 'view']);
    Route::get('{documentSubmission}/print', [DocumentSubmissionController::class, 'print']);
});

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial/import-lc-charges-entry', 'namespace' => 'SkylarkSoft\GoRMG\Commercial\Controllers\ImportLCChargesEntry'], function () {
    Route::get('/', [ImportLCChargesEntryController::class, 'index']);
});

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial/export-invoice'], function () {
    Route::get('/', [ExportInvoiceController::class, 'index']);
    Route::delete('{invoice}', [ExportInvoiceController::class, 'delete']);
    Route::get('{invoice}/view', [ExportInvoiceController::class, 'view']);
    Route::get('/{any?}', [ExportInvoiceController::class, 'createPage'])->where('any', '.*');
});


Route::group(['namespace' => 'SkylarkSoft\GoRMG\Commercial\Controllers\ImportPayment', 'middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial/import-payment'], function () {
    Route::get('', 'ImportPaymentController@index');
    Route::get('/create', 'ImportPaymentController@create');
    Route::delete('{importPayment}', 'ImportPaymentController@destroy');
});

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial/import-document-acceptance'], function () {
    Route::get('', [ImportDocumentAcceptanceAPIController::class, 'importDocumentlist']);
    Route::delete('{importDocument}', [ImportDocumentAcceptanceAPIController::class, 'deleteList']);
    Route::get('/{any}', [ImportDocumentAcceptanceController::class, 'index'])->where('any', '.*');
});

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial/primary-master-contract'], function () {
    Route::post('', [PrimaryMasterContractController::class, 'store']);
    Route::delete('/{primaryMasterContract}', [PrimaryMasterContractController::class, 'destroy']);
    Route::get('/create', [PrimaryMasterContractController::class, 'create']);
    Route::post('/store-details', [PrimaryMasterContractController::class, 'storeDetails']);
    Route::delete('/{primaryMasterContractDetails}/delete-details', [PrimaryMasterContractController::class, 'deleteDetails']);
    Route::get('/dependent-data', [PrimaryMasterContractController::class, 'dependentData']);
    Route::put('/{primaryMasterContract}', [PrimaryMasterContractController::class, 'update']);
    Route::get('/{primaryMasterContract}', [PrimaryMasterContractController::class, 'show']);
    Route::get('', [PrimaryMasterContractController::class, 'index']);
    Route::get('/{id}/edit', [PrimaryMasterContractController::class, 'create']);
    Route::get('selected-options', [ActualCostEntryController::class, 'selectedOptions']);
    Route::get('{actualCost}', [ActualCostEntryController::class, 'show']);
    Route::put('{actualCost}', [ActualCostEntryController::class, 'update'])->where('actualCost', '[0-9]+');
    Route::get('/{id}/view', [PrimaryMasterContractController::class, 'view']);
    Route::get('/{id}/pdf', [PrimaryMasterContractController::class, 'getPdf']);
    Route::get('/search', [PrimaryMasterContractController::class, 'search']);

    //Primary Contract
    Route::get('agent-primary-contracts/{id}', [PrimaryMasterContractController::class, 'agentPrimaryContracts']);
    Route::get('primary-contract-value/{id}', [PrimaryMasterContractController::class, 'getPrimaryContract']);

});


Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial/primary-master-contract-amendments'], function () {
    Route::get('', [PrimaryMasterContractAmendmentController::class, 'index']);
    Route::get('/fetch-contract-no', [PrimaryMasterContractAmendmentController::class, 'fetchContractNo']);
    Route::get('/{uniqueId}/get-master-contract', [PrimaryMasterContractAmendmentController::class, 'getMasterContractData']);
    Route::get('/create', [PrimaryMasterContractAmendmentController::class, 'create']);
    Route::post('', [PrimaryMasterContractAmendmentController::class, 'store']);
    Route::delete('/{primaryMasterContract}', [PrimaryMasterContractController::class, 'destroy']);
    Route::post('/store-details', [PrimaryMasterContractController::class, 'storeDetails']);
    Route::delete('/{primaryMasterContractDetails}/delete-details', [PrimaryMasterContractController::class, 'deleteDetails']);
    Route::get('/dependent-data', [PrimaryMasterContractController::class, 'dependentData']);
    Route::put('/{primaryMasterContract}', [PrimaryMasterContractController::class, 'update']);
    Route::get('/{primaryMasterContract}', [PrimaryMasterContractController::class, 'show']);
    Route::get('/{id}/edit', [PrimaryMasterContractController::class, 'create']);
    Route::get('selected-options', [ActualCostEntryController::class, 'selectedOptions']);
    Route::get('{actualCost}', [ActualCostEntryController::class, 'show']);
    Route::put('{actualCost}', [ActualCostEntryController::class, 'update'])->where('actualCost', '[0-9]+');
});

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial/pre-export-finance'], function () {
    Route::get('/{any}', [PreExportFinanceController::class, 'index'])->where('any', '.*');
});

Route::group(['middleware' => 'web', 'prefix' => 'commercial/btb-lc-amendment'], function () {
    Route::get('', [BtbLcAmendmentController::class, 'index']);
    Route::get('/create', [BtbLcAmendmentController::class, 'create']);
});

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial/actual-cost-entry'], function () {
    Route::get('', [ActualCostEntryController::class, 'index']);
    Route::delete('{actualCost}', [ActualCostEntryController::class, 'destroy']);
    Route::get('/create', [ActualCostEntryController::class, 'create']);
});

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'commercial', 'namespace' => 'SkylarkSoft\GoRMG\Commercial\Controllers'], function () {
    Route::get('pmc-btb-status', [PrimaryContractBTBStatusReportController::class, 'view']);
    Route::get('pmc-btb-status/get-excel', [PrimaryContractBTBStatusReportController::class, 'getExcel']);

    Route::get('contract-lc-status', [CommercialReportController::class, 'contractLcStatus']);
    Route::get('contract-lc-status-pdf', [CommercialReportController::class, 'contractLcStatusPdf']);

    Route::get('btb-status', [CommercialReportController::class, 'btbStatus']);
    Route::get('btb-status-pdf', [CommercialReportController::class, 'btbStatusPdf']);

    Route::get('export-lc-status', [CommercialReportController::class, 'exportLcStatus']);
    Route::get('export-lc-status-pdf', [CommercialReportController::class, 'exportLcStatusPdf']);

    //Reports
    Route::get('btb-liability-coverage', [CommercialReportController::class, 'btbLiabilityCoverage']);
    Route::get('btb-liability-coverage-pdf', [CommercialReportController::class, 'btbLiabilityCoveragePdf']);

    Route::get('export-ci-statement', [CommercialReportController::class, 'exportCiStatement']);
    Route::get('export-ci-statement-pdf', [CommercialReportController::class, 'exportCiStatementPdf']);

    Route::get('export-import-status', [CommercialReportController::class, 'exportImportStatus']);
    Route::get('export-import-status-pdf', [CommercialReportController::class, 'exportImportStatusPdf']);

    Route::get('export-lc-sales', [CommercialReportController::class, 'exportLcSales']);
    Route::get('export-lc-sales-pdf', [CommercialReportController::class, 'exportLcSalesPdf']);


    Route::get('export-statement-today', [CommercialReportController::class, 'exportStatementToday']);
    Route::get('export-statement-today-pdf', [CommercialReportController::class, 'exportStatementTodayPdf']);


    Route::get('file-wise-export-import', [CommercialReportController::class, 'fileWiseExportImport']);
    Route::get('file-wise-export-import-pdf', [CommercialReportController::class, 'fileWiseExportImportPdf']);

    Route::get('file-wise-export-status', [CommercialReportController::class, 'fileWiseExportStatus']);
    Route::get('file-wise-export-status-pdf', [CommercialReportController::class, 'fileWiseExportStatusPdf']);


    Route::get('monthly-bank-submission', [CommercialReportController::class, 'monthlyBankSubmission']);
    Route::get('monthly-bank-submission-pdf', [CommercialReportController::class, 'monthlyBankSubmissionPdf']);

    Route::get('yarn-work-order-statement', [CommercialReportController::class, 'yarnWorkOrderStatement']);
    Route::get('yarn-work-order-statement-pdf', [CommercialReportController::class, 'yarnWorkOrderStatementPdf']);

    Route::get('monthly-export-import', [CommercialReportController::class, 'monthlyExportImport']);
    Route::get('monthly-export-import-pdf', [CommercialReportController::class, 'monthlyExportImportPdf']);

    Route::get('order-wise-export-invoice', [CommercialReportController::class, 'orderWiseExportInvoice']);
    Route::get('order-wise-export-invoice-pdf', [CommercialReportController::class, 'orderWiseExportInvoicePdf']);

    Route::group(['prefix' => 'performance-report'], function () {
        Route::get('/', [PerformanceReportController::class, 'index']);
        Route::get('/get', [PerformanceReportController::class, 'getReport']);
        Route::get('/pdf', [PerformanceReportController::class, 'getReportPdf']);
        Route::get('/excel', [PerformanceReportController::class, 'getReportExcel']);
        Route::get('/fetch-bank-file-nos', [PerformanceReportController::class, 'fetchBankFileNos']);
        Route::get('/fetch-bank-file-data', [PerformanceReportController::class, 'fetchBankFileData']);
    });

    Route::group(['prefix' => 'pi-tracking-report'], function () {
        Route::get('/', [PITrackingReportController::class, 'index']);
        Route::get('/get', [PITrackingReportController::class, 'getReport']);
        Route::get('/pdf', [PITrackingReportController::class, 'getReportPdf']);
        Route::get('/excel', [PITrackingReportController::class, 'getReportExcel']);
    });
    Route::get('/get_buyer/{buyer}', 'CommercialCommonAPI@get_buyer');
    Route::get('/get_lien_bank/{bank}', 'CommercialCommonAPI@get_lien_bank');
});

Route::get('/test-menu', function () {
    return session('menu');
});

Route::get('/flush', function () {
    session()->flush();

    return session('menu');
});

Route::get('test-array-merge', function () {
    $arr1 = [];
    $arr1['key1'] = 1;
    $arr1['key2'] = 2;

    return $arr1;
});
