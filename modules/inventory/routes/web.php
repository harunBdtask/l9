<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsInventory\TNADateApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsInventory\TrimsBookingDetailsApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsInventory\TrimsInventoryBinNoApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsInventory\TrimsInventoryController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsInventory\BookingDataApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsInventory\TrimsInventoryDetailsController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsInventory\TrimsInventoryNoApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreBinCard\BookingWiseInventoryController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreBinCard\TrimsBinCardDetailsController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreBinCard\TrimsStoreBinCardController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreBinCard\TrimsStoreBinNoApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreBinCard\TrimsStoreMrrDateApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreIssue\InventoryDataApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreIssue\TrimsBinCardApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreMrr\TrimsMrrNoApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreMrr\TrimsReceiveApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreMrr\TrimsStoreMrrApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreBinCard\TrimsStoreBinCardNoApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreReceive\TrimsStoreReceiveController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreMrr\TrimsStoreMrrController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreMrr\TrimsStoreMrrDetailsController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreIssue\TrimsStoreIssueController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreIssue\TrimsStoreIssueDetailsController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreDeliveryChallan\TrimsStoreDeliveryChallanController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreDeliveryChallan\TrimsStoreDeliveryChallanDetailsController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreDeliveryChallan\DeliveryChallanWiseBookingNoApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreReceive\TrimsStoreReceiveDetailsController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreReceive\TrimsStoreReceiveNoApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\Reports\TrimsStore\TrimsStoreDailyDetailsReportController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnAPIController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\StoreApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\StyleApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\CommonApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\Reports\FinishFabricMonthlyStockReportController;
use SkylarkSoft\GoRMG\Inventory\Controllers\Reports\GoodReceivedWithLCOpenReportController;
use SkylarkSoft\GoRMG\Inventory\Controllers\Reports\TrimsStore\TrimsStoreMonthlyStockUpReportController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\CurrencyController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\FabricIssueController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\StoreBinsApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\Stores\StoreController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsReceiveController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\FabricReceiveController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\StoreRacksApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\StoreRoomsApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\StoreShelfApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsIssueApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\FabricTransferController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\StoreFloorsApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\StoreDetailsApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\Stores\StoreBinController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\Stores\StoreRackController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\Stores\StoreRoomController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsIssueReturnController;
use SkylarkSoft\GoRMG\Inventory\Controllers\Reports\YarnStockSummaryReport;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\FabricIssueReturnController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\Stores\StoreFloorController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\Stores\StoreShelfController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\BuyerStyleWisePONoController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\FetchDyeingCompanyController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsReceiveReturnController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\FabricBarcodeDetailController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\FabricReceiveReturnController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnIssue\YarnIssueController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\FabricCompositionApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\Reports\YarnReceiveReportController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\FetchFabricBookingsApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnIssue\IssueChallanController;
use SkylarkSoft\GoRMG\Inventory\Controllers\Reports\FinishFabricReportController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\FabricReceiveDefaultApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\GreyReceive\GreyReceiveController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceive\YarnReceiveController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceive\YarnTypeApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsReceiveReturnSearchController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceive\YarnStoreApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\Reports\YarnIssueDailyReportController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\GreyDelivery\GreyDeliveryController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsOrderToOrderTransferController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnTransfer\YarnTransferController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceive\YarnSupplierApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceive\YarnWorkOrderApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\Reports\FabricStockSummeryReportController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\FabricStoreVariableSettingApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnItemLedger\YarnItemLedgerController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceive\YarnReceiveDetailsController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnIssue\YarnRequisitionSearchController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnIssueReturn\YarnIssueReturnController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnTransfer\YarnTransferDetailController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\FetchProformaInvoiceApiControllerController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceive\YarnProformaInvoiceApiController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceiveReturn\YarnReceiveReturnController;
use SkylarkSoft\GoRMG\Inventory\Controllers\Reports\FinishFabricReceiveReportController;
use SkylarkSoft\GoRMG\Inventory\Controllers\Reports\FinishFabricIssueReportController;
use SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnGatePass\YarnGatePassChallanScanController;

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'inventory', 'namespace' => 'SkylarkSoft\GoRMG\Inventory\Controllers'], function () {
    Route::get('trims-receives', [TrimsReceiveController::class, 'index']);
    Route::get('trims-receives/create', [TrimsReceiveController::class, 'create']);
    Route::get('trims-receives/{id}/edit', [TrimsReceiveController::class, 'create']);
    Route::get('trims-receives/{id}/view', [TrimsReceiveController::class, 'view']);
    Route::get('trims-receives/{id}/pdf', [TrimsReceiveController::class, 'pdf']);


    Route::get('trims-issue', [TrimsIssueApiController::class, 'create']);
    Route::get('trims-issue/{id}/edit', [TrimsIssueApiController::class, 'create']);
    Route::get('trims-issue/list', [TrimsIssueApiController::class, 'index']);
    Route::get('trims-issue/{id}/view', [TrimsIssueApiController::class, 'view']);
    Route::get('trims-issue/{id}/pdf', [TrimsIssueApiController::class, 'pdf']);

    Route::get('trims-receive-return', [TrimsReceiveReturnController::class, 'create']);
    Route::get('trims-receive-return/{id}/edit', [TrimsReceiveReturnController::class, 'create']);
    Route::get('trims-receive-returns', [TrimsReceiveReturnController::class, 'index']);

    Route::get('trims-issue-return/create', [TrimsIssueReturnController::class, 'create']);
    Route::get('trims-issue-return', [TrimsIssueReturnController::class, 'index']);

    Route::get('trims-order-transfer', [TrimsOrderToOrderTransferController::class, 'index']);
    Route::get('trims-order-transfer/create', [TrimsOrderToOrderTransferController::class, 'create']);
    Route::get('trims-order-transfer/{id}/edit', [TrimsOrderToOrderTransferController::class, 'create']);

    Route::view('store-details', 'inventory::store-details.store_details');
    Route::view('trims-issue-return/create', 'inventory::trims.trims-issue-return');
    Route::view('store-managements', 'inventory::stores.index');

    // fabric receive
    Route::get('fabric-receives/create', [FabricReceiveController::class, 'create']);
    Route::get('fabric-receives/{id}/edit', [FabricReceiveController::class, 'create']);
    Route::delete('fabric-receives/{receive}/delete', [FabricReceiveController::class, 'destroy']);
    Route::get('fabric-receives', [FabricReceiveController::class, 'index']);
    Route::get('fabric-receives/{receive}/view', [FabricReceiveController::class, 'view']);
    Route::get('fabric-receives/{receive}/barcodes', [FabricReceiveController::class, 'barcodes']);
    Route::get('fabric-receives/{receive}/pdf', [FabricReceiveController::class, 'pdf']);
    Route::get('fabric-receives/{receive}/excel', [FabricReceiveController::class, 'excel']);
    Route::get('dyeing-company', [FetchDyeingCompanyController::class, '__invoke']);

    Route::get('/finish-fabric-receive-report', [FinishFabricReceiveReportController::class, 'finishFabricReceiveReport']);
    Route::get('/date-wise-finish-fabric-receive-report', [FinishFabricReceiveReportController::class, 'dateWiseFinishFabricReceiveReport']);
    Route::get('/finish-fabric-receive-report-pdf', [FinishFabricReceiveReportController::class, 'pdf']);
    Route::get('/finish-fabric-receive-report-excel', [FinishFabricReceiveReportController::class, 'excel']);

    Route::get('/finish-fabric-issue-report', [FinishFabricIssueReportController::class, 'finishFabricIssueReport']);
    Route::get('/date-wise-finish-fabric-issue-report', [FinishFabricIssueReportController::class, 'dateWiseFinishFabricIssueReport']);
    Route::get('/finish-fabric-issue-report-pdf', [FinishFabricIssueReportController::class, 'pdf']);
    Route::get('/finish-fabric-issue-report-excel', [FinishFabricIssueReportController::class, 'excel']);

    Route::get('/finish-fabric-monthly-stock-report', [FinishFabricMonthlyStockReportController::class, 'finishFabricStockReport']);
    Route::get('/date-wise-finish-fabric-monthly-stock-report', [FinishFabricMonthlyStockReportController::class, 'dateWiseFinishFabricStockReport']);
    Route::get('/finish-fabric-stock-report-excel', [FinishFabricMonthlyStockReportController::class, 'excel']);

    Route::get('get-currency/{name}', [YarnAPIController::class, 'getCurrencyIdByName']);
    Route::get('/yarn-type-get', [YarnTypeApiController::class, '__invoke']);

    Route::get('/yarn-stock-item-wise-list', [YarnStockSummaryReport::class, 'yarnStockItemWiseList']);
    Route::get('/yarn-stock-summary-report', [YarnStockSummaryReport::class, 'yarnStockSummaryReport']);
    Route::get('/yarn-stock-summary-supplier-lot-wise-report', [YarnStockSummaryReport::class, 'yarnStockSummarySupplierLotReport']);

    Route::get('/daily-yarn-receive-statement', [YarnReceiveReportController::class, 'getDailyStatement']);
    Route::get('/challan-wise-receive-statement', [YarnReceiveReportController::class, 'getChallanWiseStatement']);
    Route::group(['prefix' => 'yarn-store'], function () {
        Route::get('/goods-receive-with-lc', [YarnReceiveReportController::class, 'getGoodsReceiveWithLC']);
        Route::get('/goods-receive-without-lc', [YarnReceiveReportController::class, 'getGoodsReceiveWithoutLC']);
    });
    Route::group(['prefix' => 'yarn-receive'], function () {
        Route::get('', [YarnReceiveController::class, 'index']);
        Route::delete('{id}/delete', [YarnReceiveController::class, 'destroy']);
        Route::get('/{any?}', [YarnReceiveController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => 'good-received-with-lc-open'], function () {
        Route::get('', [GoodReceivedWithLCOpenReportController::class, 'index']);
        Route::get('get-report', [GoodReceivedWithLCOpenReportController::class, 'getReport']);
        Route::get('pdf', [GoodReceivedWithLCOpenReportController::class, 'pdf']);

    });

    Route::group(['prefix' => 'yarn-gate-pass-challan-scan'], function () {
        Route::get('', [YarnGatePassChallanScanController::class, 'index']);
        Route::get('/search', [YarnGatePassChallanScanController::class, 'search']);
        Route::post('/store', [YarnGatePassChallanScanController::class, 'store']);
        Route::get('/show', [YarnGatePassChallanScanController::class, 'show']);
    });

    Route::get('yarn-receive-ids', [YarnReceiveReturnController::class, 'getReceiveIds']);
    Route::get('yarn-receive-details', [YarnReceiveReturnController::class, 'getReceiveDetails']);
    Route::get('get-yarn-receives', [YarnReceiveReturnController::class, 'getYarnReceives']);
    Route::get('receive-returns/{id}', [YarnReceiveReturnController::class, 'getReceiveReturns']);
    Route::post('yarn-receive-return-details', [YarnReceiveReturnController::class, 'storeDetails']);
    Route::delete('delete-yarn-receive-detail/{id}', [YarnReceiveReturnController::class, 'deleteYarnReceiveDetail']);

    Route::group(['prefix' => 'daily-yarn-issue-report'], function () {
        Route::get('/{type?}', [YarnIssueDailyReportController::class, 'index']);
    });
    Route::group(['prefix' => 'yarn-receive-return'], function () {
        Route::get('', [YarnReceiveReturnController::class, 'index']);
        Route::post('', [YarnReceiveReturnController::class, 'store']);
        Route::post('store-details', [YarnReceiveReturnController::class, 'storeDetails']);
        Route::get('/create', [YarnReceiveReturnController::class, 'create']);
        Route::get('/{id}/view-details', [YarnReceiveReturnController::class, 'create']);
        Route::get('/{id}', [YarnReceiveReturnController::class, 'show']);
        Route::get('/{id}/delete', [YarnReceiveReturnController::class, 'deleteYarnReceiveReturn']);
        Route::get('/{id}/print', [YarnReceiveReturnController::class, 'print']);
        //
        //        Route::get('/{any?}', [YarnReceiveReturnController::class, 'create'])
        //            ->where('any', '.*');
    });

    Route::group(['prefix' => 'yarn-issue-return'], function () {
        Route::get('', [YarnIssueReturnController::class, 'index']);
        Route::get('/{id}/delete', [YarnIssueReturnController::class, 'delete']);

        Route::get('/{id}/view', [YarnIssueReturnController::class, 'view']);
        Route::get('/{id}/print', [YarnIssueReturnController::class, 'print']);

        Route::get('/{any?}', [YarnIssueReturnController::class, 'create'])
            ->where('any', '.*');
    });

    // ========= Yarn Issue ======== //
    Route::group(['prefix' => 'yarn-issue'], function () {
        Route::get('', [YarnIssueController::class, 'index']);
        Route::post('', [YarnIssueController::class, 'store']);
        Route::get('create', [YarnIssueController::class, 'create']);
        Route::get('{id}/view', [YarnIssueController::class, 'view']);
        Route::get('/{id}/show', [YarnIssueController::class, 'show']);
        Route::get('{id}/edit', [YarnIssueController::class, 'create']);
        Route::get('{id}/print', [YarnIssueController::class, 'print']);
        Route::get('{id}/approval', [YarnIssueController::class, 'approval']);
        Route::delete('/{id}/delete', [YarnIssueController::class, 'delete']);

        Route::group(['prefix' => 'challan'], function () {
            Route::get('{id}/pdf', [IssueChallanController::class, 'pdf']);
            Route::get('{id}/view', [IssueChallanController::class, 'index']);
            Route::get('{id}/print', [IssueChallanController::class, 'print']);

            Route::get('{id}/yarn-challan-pdf', [IssueChallanController::class, 'yarnChallanPdf']);
            Route::get('{id}/yarn-challan-view', [IssueChallanController::class, 'yarnChallanView']);
            Route::get('{id}/yarn-challan-print', [IssueChallanController::class, 'YarnChallanPrint']);
        });

        Route::get('/get-yarn-receive', [YarnIssueController::class, 'getYarnReceive']);
        Route::get('/get-yarn-lot/{supplierId}', [YarnIssueController::class, 'getYarnLot']);
        Route::get('/{id}/get-garment-sample', [YarnIssueController::class, 'getGarmentType']);
    });
    Route::group(['prefix' => 'yarn-issue-details'], function () {
        Route::post('', [YarnIssueController::class, 'storeDetails']);
        Route::get('{id}/delete', [YarnIssueController::class, 'deleteDetails']);
        Route::get('requisition-validation-data/{id}', [YarnIssueController::class, 'requisitionValidationData']);
    });
    // ========= Yarn Transfer ======== //
    Route::group(['prefix' => 'yarn-transfer'], function () {
        Route::get('/', [YarnTransferController::class, 'index']);
        Route::get('/create', [YarnTransferController::class, 'create']);
        Route::post('/', [YarnTransferController::class, 'store']);
        Route::get('/{id}/edit', [YarnTransferController::class, 'create']);

        Route::get('/{id}/delete', [YarnTransferController::class, 'delete']);
        Route::get('/{id}/view', [YarnTransferController::class, 'view']);
        Route::get('/{id}/print', [YarnTransferController::class, 'print']);

        Route::get('/{transfer}/get-transfer-data', [YarnTransferController::class, 'show']);

        Route::post('/store-details', [YarnTransferDetailController::class, 'store']);
        Route::get('/{id}/details', [YarnTransferDetailController::class, 'show']);
        Route::delete('/{detail}/details', [YarnTransferDetailController::class, 'delete']);
    });

    // ========= Yarn Item Ledger ======== //
    Route::group(['prefix' => 'yarn-item-ledger'], function () {
        Route::get('/', [YarnItemLedgerController::class, 'index']);
        Route::post('/report/excel', [YarnItemLedgerController::class, 'excelReport']);
        Route::post('/report/pdf', [YarnItemLedgerController::class, 'pdfReport']);
    });

    // daily yarn stock
    Route::view('daily-yarn-stock', 'inventory::yarns.reports.daily-yarn-stock');

    // fabric receive return
    // Route::view('fabric-receive-returns/{any?}', 'inventory::fabrics.receive-returns')->where('any', '.*');

    Route::get('fabric-receive-returns', [FabricReceiveReturnController::class, 'index']);
    Route::get('fabric-receive-returns/create', [FabricReceiveReturnController::class, 'create']);
    Route::get('fabric-receive-returns/{id}/edit', [FabricReceiveReturnController::class, 'create']);
    // Fabric Issue...
    Route::get('fabric-issues', [FabricIssueController::class, 'index']);
    Route::get('fabric-issues/create', [FabricIssueController::class, 'create']);
    Route::get('fabric-issues/{id}/edit', [FabricIssueController::class, 'create']);
    Route::get('fabric-issues/{issue}/view', [FabricIssueController::class, 'view']);
    Route::get('fabric-issues/{issue}/pdf', [FabricIssueController::class, 'pdf']);
    Route::get('fabric-issues/{issue}/excel', [FabricIssueController::class, 'excel']);


    // Fabric Issue Return...
    Route::get('fabric-issue-returns', [FabricIssueReturnController::class, 'index']);
    Route::get('fabric-issue-returns/create', [FabricIssueReturnController::class, 'create']);
    Route::get('fabric-issue-returns/{id}/edit', [FabricIssueReturnController::class, 'create']);

    // Fabric Transfer...
    Route::get('fabric-transfers', [FabricTransferController::class, 'index']);
    Route::get('fabric-transfers/create', [FabricTransferController::class, 'create']);
    Route::get('fabric-transfers/{id}/edit', [FabricTransferController::class, 'create']);

    Route::group(['prefix' => 'finish-fabric-report'], function () {
        Route::get('', [FinishFabricReportController::class, 'index']);
        Route::post('/get-report', [FinishFabricReportController::class, 'getReport']);
        Route::get('/get-report-pdf', [FinishFabricReportController::class, 'getReportPdf']);
        Route::get('/get-report-excel', [FinishFabricReportController::class, 'getReportExcel']);
    });

    Route::group(['prefix' => 'fabric-stock-summery-report'], function () {
        Route::get('', [FabricStockSummeryReportController::class, 'index']);
        Route::post('get-report-data', [FabricStockSummeryReportController::class, 'reportData']);
        Route::get('get-report-pdf', [FabricStockSummeryReportController::class, 'pdf']);
        Route::get('get-report-excel', [FabricStockSummeryReportController::class, 'excel']);
    });

    Route::group((['prefix' => 'grey-receive']), function () {
        Route::delete('/{greyReceive}', [GreyReceiveController::class, 'destroy']);
        Route::get('/create', [GreyReceiveController::class, 'create']);
        Route::post('/{greyReceive}/store-details', [GreyReceiveController::class, 'storeDetails']);
        Route::get('/fetch-challan', [GreyReceiveController::class, 'fetchChallan']);
        Route::get('/fetch-challan-details', [GreyReceiveController::class, 'fetchChallanDetails']);
        Route::get('/', [GreyReceiveController::class, 'index']);
        Route::get('/{greyReceive}', [GreyReceiveController::class, 'show']);
        Route::post('/', [GreyReceiveController::class, 'store']);
        Route::get('/{id}/edit', [GreyReceiveController::class, 'create']);
    });

    Route::group(['prefix' => 'grey-delivery'], function () {
        Route::get('/create', [GreyDeliveryController::class, 'create']);
        Route::delete('/{greyDelivery}', [GreyDeliveryController::class, 'destroy']);
        Route::get('/fetch-challan-details', [GreyDeliveryController::class, 'getDetails']);
        Route::delete('/{greyDeliveryDetail}/delete', [GreyDeliveryController::class, 'destroyDetail']);
        Route::post('/{greyDelivery}/store-details', [GreyDeliveryController::class, 'storeDetails']);
        Route::get('/{greyDelivery}', [GreyDeliveryController::class, 'show']);
        Route::get('/{id}/edit', [GreyDeliveryController::class, 'create']);
        Route::get('/', [GreyDeliveryController::class, 'index']);
        Route::post('/', [GreyDeliveryController::class, 'store']);
    });

    Route::group(['prefix' => 'trims-store'], function () {

        Route::group(['prefix' => 'inventory'], function () {
            Route::get('', [TrimsInventoryController::class, 'index']);
            Route::get('/view/{id}', [TrimsInventoryController::class, 'view']);
            Route::get('/pdf/{id}', [TrimsInventoryController::class, 'pdf']);
            Route::get('/excel/{id}', [TrimsInventoryController::class, 'excel']);
            Route::get('/{any?}', [TrimsInventoryController::class, 'create']);
        });

        Route::group(['prefix' => 'receive'], function () {
            Route::get('', [TrimsStoreReceiveController::class, 'index']);
            Route::get('/view/{receive}', [TrimsStoreReceiveController::class, 'view']);
            Route::get('/pdf/{receive}', [TrimsStoreReceiveController::class, 'pdf']);
            Route::get('/excel/{receive}', [TrimsStoreReceiveController::class, 'excel']);
            Route::get('/{any?}', [TrimsStoreReceiveController::class, 'create']);
        });

        Route::group(['prefix' => 'mrr'], function () {
            Route::get('', [TrimsStoreMrrController::class, 'index']);
            Route::get('/view/{mrr}', [TrimsStoreMrrController::class, 'view']);
            Route::get('/pdf/{mrr}', [TrimsStoreMrrController::class, 'pdf']);
            Route::get('/excel/{mrr}', [TrimsStoreMrrController::class, 'excel']);
            Route::get('/{any?}', [TrimsStoreMrrController::class, 'create']);
        });

        Route::group(['prefix' => 'bin-card'], function () {
            Route::get('', [TrimsStoreBinCardController::class, 'index']);
            Route::get('/view/{binCard}', [TrimsStoreBinCardController::class, 'view']);
            Route::get('/pdf/{binCard}', [TrimsStoreBinCardController::class, 'pdf']);
            Route::get('/excel/{binCard}', [TrimsStoreBinCardController::class, 'excel']);
            Route::get('/{any?}', [TrimsStoreBinCardController::class, 'create']);
        });

        Route::group(['prefix' => 'issue'], function () {
            Route::get('/', [TrimsStoreIssueController::class, 'index']);
            Route::get('/view/{issue}', [TrimsStoreIssueController::class, 'view']);
            Route::get('/pdf/{issue}', [TrimsStoreIssueController::class, 'pdf']);
            Route::get('/excel/{issue}', [TrimsStoreIssueController::class, 'excel']);
            Route::get('/{any?}', [TrimsStoreIssueController::class, 'create']);
        });

        Route::group(['prefix' => 'delivery-challan'], function () {
            Route::get('/', [TrimsStoreDeliveryChallanController::class, 'index']);
            Route::get('{challanNo}/view', [TrimsStoreDeliveryChallanController::class, 'view']);
            Route::get('{challanNo}/pdf', [TrimsStoreDeliveryChallanController::class, 'pdf']);
            Route::get('{challanNo}/excel', [TrimsStoreDeliveryChallanController::class, 'excel']);
            Route::get('/{any?}', [TrimsStoreDeliveryChallanController::class, 'create']);
        });

        Route::group(['prefix' => 'reports'], function () {
            Route::group(['prefix' => 'daily-details-report'], function () {
                Route::get('/', [TrimsStoreDailyDetailsReportController::class, 'index']);
                Route::get('/pdf', [TrimsStoreDailyDetailsReportController::class, 'pdf']);
                Route::get('/excel', [TrimsStoreDailyDetailsReportController::class, 'excel']);
                Route::get('/get', [TrimsStoreDailyDetailsReportController::class, 'getReport']);
            });

            Route::group(['prefix' => 'monthly-stock-up-report'], function () {
                Route::get('', [TrimsStoreMonthlyStockUpReportController::class, 'index']);
                Route::get('fetch-report-data', [TrimsStoreMonthlyStockUpReportController::class, 'getReport']);
                Route::get('pdf', [TrimsStoreMonthlyStockUpReportController::class, 'pdf']);
                Route::get('excel', [TrimsStoreMonthlyStockUpReportController::class, 'excel']);
            });
        });

    });

});

Route::group([
    'prefix' => 'inventory-api/v1',
    'middleware' => ['web', 'auth', 'menu-auth'],
    'namespace' => 'SkylarkSoft\GoRMG\Inventory\Controllers\API'], function () {
    // yarn_issue search Requisition
    Route::get('search-requisition', [YarnRequisitionSearchController::class, '__invoke']);
    Route::get('yarn-issue-requisition-search-filters', [YarnIssueController::class, 'requisitionSearchFilterData']);

    Route::get('fetch-floors', [StoreFloorsApiController::class, 'index']);
    Route::get('fetch-rooms/{floorId}', [StoreRoomsApiController::class, 'index']);
    Route::get('fetch-racks/{floorId}/{roomId}', [StoreRacksApiController::class, 'index']);
    Route::get('fetch-shelf/{floorId}/{roomId}/{rackId}', [StoreShelfApiController::class, 'index']);
    Route::get('fetch-bins/{floorId}/{roomId}/{rackId}/{shelfId}', [StoreBinsApiController::class, 'index']);
    // Store Details Api's...
    Route::get('store-details', [StoreDetailsApiController::class, 'index']);
    Route::post('store-details', [StoreDetailsApiController::class, 'store']);
    Route::get('store-details-options', [StoreDetailsApiController::class, 'getStoreOptions']);
    Route::delete('store-details/{storeDetail}', [StoreDetailsApiController::class, 'destroy']);
    Route::get('get-store-details', [StoreDetailsApiController::class, 'previousStoreDetails']);

    Route::get('currency-options', [CurrencyController::class, 'getOptions']);

    Route::get('fetch-styles/{buyerId}', [StyleApiController::class, '__invoke']);
    Route::get('fetch-po-no/{buyerId}/{styleId}', [BuyerStyleWisePONoController::class, '__invoke']);

    // Store api...
    Route::get('fetch-stores', [StoreApiController::class, '__invoke']);

    Route::group(['prefix' => 'yarn-receive'], function () {
        Route::get('/yarn-types', 'YarnTypeApiController');
        Route::get('/yarn-counts', 'YarnCountApiController');
        Route::get('/loan-parties', 'LoanPartyApiController');
        Route::get('/yarn-compositions', 'YarnCompositionApiController');
        Route::get('/receive-nos', [YarnReceiveController::class, 'getReceiveIds']);
        Route::group(['namespace' => 'YarnReceive'], function () {
            Route::post('', [YarnReceiveController::class, 'store']);
            Route::get('/stores', [YarnStoreApiController::class, '__invoke']);
            Route::post('/details', [YarnReceiveDetailsController::class, 'store']);
            Route::get('/suppliers', [YarnSupplierApiController::class, '__invoke']);
            Route::get('/work-order', [YarnWorkOrderApiController::class, '__invoke']);
            Route::get('/proforma-invoice', [YarnProformaInvoiceApiController::class, '__invoke']);
            Route::delete('/details/{id}/delete', [YarnReceiveDetailsController::class, 'destroy']);
            Route::get('/{yarn_receive_id}/details', [YarnReceiveDetailsController::class, 'show']);
            Route::get('/{id}/edit', [YarnReceiveController::class, 'edit']);
        });
    });

    Route::group(['prefix' => 'trims-receives'], function () {
        Route::get('trims-search', [TrimsReceiveController::class, 'searchTrimsFromPIorBooking']);
        Route::post('', [TrimsReceiveController::class, 'store']);
        Route::post('{receive}/save-details-data', [TrimsReceiveController::class, 'storeDetails']);
        Route::get('{receive}', [TrimsReceiveController::class, 'show']);
        Route::delete('{receive}', [TrimsReceiveController::class, 'delete']);
        Route::delete('{trimsReceiveDetail}/delete-detail-data', [TrimsReceiveController::class, 'destroyDetails']);
    });

    Route::group(['prefix' => 'trims-issues'], function () {
        Route::get('/fetch-receive-details', [TrimsIssueApiController::class, 'getReceiveDetails']);
        Route::get('/fetch-receive-items-details', [TrimsIssueApiController::class, 'getReceiveItemDetails']);

        Route::post('', [TrimsIssueApiController::class, 'store']);
        Route::put('{trimsIssue}/update', [TrimsIssueApiController::class, 'store']);
        Route::get('{trimsIssue}/show', [TrimsIssueApiController::class, 'show']);
        Route::get('{trimsIssue}/edit', [TrimsIssueApiController::class, 'show']);
        Route::delete('{trimsIssue}/delete', [TrimsIssueApiController::class, 'destroy']);
    });

    Route::group(['prefix' => 'trims-receive-return'], function () {
        Route::get('/filter', [TrimsReceiveReturnController::class, 'filter']);
        Route::post('', [TrimsReceiveReturnController::class, 'store']);
        Route::post('/save-details/{trimsReceiveReturn}', [TrimsReceiveReturnController::class, 'storeDetails']);
        Route::post('/main-section-validation', [TrimsReceiveReturnController::class, 'validateMainSection']);
        Route::delete('{trimsReceiveReturn}/delete', [TrimsReceiveReturnController::class, 'destroy']);
        Route::get('/{trimsReceiveReturn}/edit', [TrimsReceiveReturnController::class, 'show']);
        Route::get('/search-received-id', [TrimsReceiveReturnSearchController::class, 'searchReceiveIdForStyle']);
        Route::get('/item-stocks-for-receive/{trimsReceive}', [TrimsReceiveReturnSearchController::class, 'itemStocksForReceive']);
        Route::get('/item-details-from-receive/{trimsReceive}', [TrimsReceiveReturnSearchController::class, 'itemDetailsFromReceive']);
    });

    Route::group(['prefix' => 'trims-issue-return'], function () {
        Route::get('fetch-issue-details', [TrimsIssueReturnController::class, 'getIssueDetails']);
        Route::post('fetch-issue-details-wise-po', [TrimsIssueReturnController::class, 'getIssueDetailsWisePoDetails']);
        Route::post('', [TrimsIssueReturnController::class, 'store']);
    });

    Route::group(['prefix' => 'store-managements'], function () {
        Route::get('', [StoreController::class, 'index']);
        Route::post('{store}', [StoreFloorController::class, 'store']);
        Route::post('{store}/{floor}', [StoreRoomController::class, 'store']);
        Route::post('{store}/{floor}/{room}', [StoreRackController::class, 'store']);
        Route::post('{store}/{floor}/{room}/{rack}', [StoreShelfController::class, 'store']);
        Route::post('{store}/{floor}/{room}/{rack}/{shelf}', [StoreBinController::class, 'store']);
        Route::delete('floor/{floor}', [StoreFloorController::class, 'destroy']);
        Route::delete('room/{room}', [StoreRoomController::class, 'destroy']);
        Route::delete('rack/{rack}', [StoreRackController::class, 'destroy']);
        Route::delete('shelf/{shelf}', [StoreShelfController::class, 'destroy']);
        Route::delete('bin/{bin}', [StoreBinController::class, 'destroy']);
    });

    Route::group(['prefix' => 'trims-order-transfer'], function () {
        Route::get('fetch-trims-receive-details', [TrimsOrderToOrderTransferController::class, 'getReceiveDetails']);
        Route::post('', [TrimsOrderToOrderTransferController::class, 'store']);
        Route::get('{trimsOrderToOrderTransfer}/edit', [TrimsOrderToOrderTransferController::class, 'show']);
        Route::put('{trimsOrderToOrderTransfer}', [TrimsOrderToOrderTransferController::class, 'update']);
        Route::delete('{trimsOrderToOrderTransfer}', [TrimsOrderToOrderTransferController::class, 'destroy']);
    });

    Route::group(['prefix' => 'fabric-receives'], function () {
        Route::get('receives-search', [FabricReceiveController::class, 'searchFabricFromPIorBooking']);
        Route::get('fetch-fabric-receive-items-details', [FabricReceiveController::class, 'getFabricReceiveItemDetails']);
        Route::post('', [FabricReceiveController::class, 'store']);
        Route::get('{receive}/edit', [FabricReceiveController::class, 'show']);
        Route::get('{receive}/approve', [FabricReceiveController::class, 'approve']);
        Route::get('{details}/details', [FabricReceiveController::class, 'showDetails']);
        Route::put('{fabric}/update', [FabricReceiveController::class, 'store']);
        Route::put('{receiveDetail}/detail/update', [FabricReceiveController::class, 'detailUpdate']);
        Route::delete('{detail}/detail', [FabricReceiveController::class, 'deleteDetail']);
        Route::get('defaults', [FabricReceiveDefaultApiController::class, '__invoke']);
        Route::get('fetch-fabric-bookings', [FetchFabricBookingsApiController::class, '__invoke']);
        Route::get('fetch-proforma-invoice', [FetchProformaInvoiceApiControllerController::class, '__invoke']);
    });

    Route::group(['prefix' => 'fabric-barcode-details'], function () {
        Route::get('{receiveDetail}/receive-detail-search', [FabricBarcodeDetailController::class, 'getFabricReceiveDetail']);
        Route::post('', [FabricBarcodeDetailController::class, 'store']);
        Route::delete('{detail}/delete', [FabricBarcodeDetailController::class, 'destroy']);
    });

    Route::group(['prefix' => 'fabric-receive-returns'], function () {
        Route::get('receive-returns-search', [FabricReceiveReturnController::class, 'searchFabricReceiveReturn']);
        Route::get('fetch-fabric-receive-return-items-details', [FabricReceiveReturnController::class, 'fabricReceiveReturnDetails']);
        Route::post('', [FabricReceiveReturnController::class, 'store']);
        Route::post('{receive}/save-detail', [FabricReceiveReturnController::class, 'storeDetails']);
        Route::post('save-details-remarks', [FabricReceiveReturnController::class, 'storeDetailsRemarks']);
        Route::get('{receiveReturn}/show', [FabricReceiveReturnController::class, 'show']);

        //    Route::get('{return}/edit', [FabricReceiveReturnController::class, 'show']);
        Route::put('{return}/update', [FabricReceiveReturnController::class, 'store']);
    });

    Route::group(['prefix' => 'fabric-issues'], function () {
        Route::get('fabric-receive-details', [FabricIssueController::class, 'getReceiveDetails']);
        Route::post('', [FabricIssueController::class, 'store']);
        Route::post('{fabricIssue}/save-issue-detail', [FabricIssueController::class, 'storeDetail']);
        Route::get('{fabricIssue}/show', [FabricIssueController::class, 'show']);
        Route::get('{issue}/approve', [FabricIssueController::class, 'approve']);
        Route::put('{fabricIssue}/update', [FabricIssueController::class, 'update']);
        Route::delete('{fabricIssueDetail}/delete-detail', [FabricIssueController::class, 'destroyDetail']);
        Route::delete('{issue}/delete', [FabricIssueController::class, 'destroy']);
        Route::put('save-remarks', [FabricIssueController::class, 'saveRemarks']);
        Route::get('get-service-company', [FabricIssueController::class, 'getServiceCompany']);
    });

    Route::group(['prefix' => 'fabric-issue-returns'], function () {
        Route::get('fetch-issue-details', [FabricIssueReturnController::class, 'getIssueDetails']);
        Route::post('', [FabricIssueReturnController::class, 'store']);
        Route::post('{fabricIssueReturn}/save-detail-data', [FabricIssueReturnController::class, 'storeDetail']);
        Route::get('{fabricIssueReturn}/show', [FabricIssueReturnController::class, 'show']);
        Route::delete('{issueReturn}/delete', [FabricIssueReturnController::class, 'destroy']);
    });

    Route::group(['prefix' => 'fabric-transfers'], function () {
        Route::get('fetch-receive-details', [FabricTransferController::class, 'getReceiveDetails']);
        Route::get('fetch-fabric-booking-detail', [FabricTransferController::class, 'transferDetail']);
        Route::post('', [FabricTransferController::class, 'store']);
        Route::post('{transfer}/save-detail-data', [FabricTransferController::class, 'storeDetail']);
        Route::get('{transfer}/edit', [FabricTransferController::class, 'show']);
        Route::get('{transferDetail}/edit-detail', [FabricTransferController::class, 'showDetail']);
        Route::put('{transfer}', [FabricTransferController::class, 'update']);
        Route::put('{transferDetail}/update-detail-data', [FabricTransferController::class, 'updateDetail']);
        Route::delete('{transfer}/delete', [FabricTransferController::class, 'destroy']);
        Route::delete('{transferDetail}/delete-detail-data', [FabricTransferController::class, 'destroyDetail']);
    });

    Route::group(['prefix' => 'yarn-issue-returns'], function () {
        Route::match(['GET', 'POST'], 'search-yarn-issue', [YarnIssueReturnController::class, 'yarnIssueSearch']);
        Route::get('yarns', [YarnIssueReturnController::class, 'issueYarns']);
        Route::get('{issueReturn}/details', [YarnIssueReturnController::class, 'details']);
        Route::post('main', [YarnIssueReturnController::class, 'store']);
        Route::post('{issueReturn}/detail', [YarnIssueReturnController::class, 'storeDetail']);
        Route::delete('main', [YarnIssueReturnController::class, 'delete']);
        Route::delete('{detail}/detail', [YarnIssueReturnController::class, 'deleteDetail']);
    });

    Route::group(['prefix' => 'yarn-item-ledger'], function () {
        Route::post('/', [YarnItemLedgerController::class, 'items']);
    });

    Route::group(['prefix' => 'trims-store'], function () {

        Route::group(['prefix' => 'inventory'], function () {
            Route::post('', [TrimsInventoryController::class, 'store']);
            Route::get('{inventory}/edit', [TrimsInventoryController::class, 'edit']);
            Route::put('{inventory}', [TrimsInventoryController::class, 'update']);
            Route::delete('{inventory}', [TrimsInventoryController::class, 'destroy']);
            Route::get('fetch-booking-nos', [TrimsInventoryController::class, 'getBookingNos']);
            Route::get('fetch-buyers', [TrimsInventoryController::class, 'getBuyers']);
            Route::get('fetch-booking-data/{bookingNo}', [BookingDataApiController::class, '__invoke']);
            Route::get('fetch-inventory-no/{bookingId}', [TrimsInventoryBinNoApiController::class, '__invoke']);
            Route::get('fetch-trims-booking-details/{trimsBooking}', [TrimsBookingDetailsApiController::class, '__invoke']);
            Route::get('fetch-trims-inventory-no', [TrimsInventoryNoApiController::class, '__invoke']);
            Route::get('fetch-tns-date/{booking:unique_id}', [TNADateApiController::class, '__invoke']);

            Route::group(['prefix' => 'details'], function () {
                Route::get('{trimsInventory}', [TrimsInventoryDetailsController::class, 'getDetails']);
                Route::post('{trimsInventory}', [TrimsInventoryDetailsController::class, 'store']);
                Route::put('{detail}', [TrimsInventoryDetailsController::class, 'update']);
                Route::delete('{detail}', [TrimsInventoryDetailsController::class, 'destroy']);
            });
        });

        Route::group(['prefix' => 'mrr'], function () {
            Route::post('', [TrimsStoreMrrController::class, 'store']);
            Route::get('{mrr}/edit', [TrimsStoreMrrController::class, 'edit']);
            Route::put('{mrr}', [TrimsStoreMrrController::class, 'update']);
            Route::delete('/{mrr}', [TrimsStoreMrrController::class, 'destroy']);
            Route::get('fetch-mrr-no/{bookingId}', [TrimsStoreMrrApiController::class, '__invoke']);
            Route::get('fetch-mrr-no', [TrimsMrrNoApiController::class, '__invoke']);
            Route::get('fetch-inventory-challan-no/{receiveId}', [TrimsReceiveApiController::class, '__invoke']);

            Route::group(['prefix' => 'details'], function () {
                Route::get('{mrr}', [TrimsStoreMrrDetailsController::class, 'getDetails']);
                Route::put('{detail}', [TrimsStoreMrrDetailsController::class, 'update']);
                Route::delete('{detail}', [TrimsStoreMrrDetailsController::class, 'destroy']);
            });
        });

        Route::group(['prefix' => 'receive'], function () {
            Route::post('', [TrimsStoreReceiveController::class, 'store']);
            Route::get('{receive}/edit', [TrimsStoreReceiveController::class, 'edit']);
            Route::put('{receive}', [TrimsStoreReceiveController::class, 'update']);
            Route::delete('{receive}', [TrimsStoreReceiveController::class, 'destroy']);
            Route::get('fetch-receive-no/{bookingNo}', [TrimsStoreReceiveNoApiController::class, '__invoke']);
            Route::get('fetch-challan-no/{id}', [InventoryDataApiController::class, '__invoke']);

            Route::group(['prefix' => 'details'], function () {
                Route::get('{receive}', [TrimsStoreReceiveDetailsController::class, 'getDetails']);
                Route::put('{detail}', [TrimsStoreReceiveDetailsController::class, 'update']);
                Route::delete('{detail}', [TrimsStoreReceiveDetailsController::class, 'destroy']);
            });
        });

        Route::group(['prefix' => 'bin-card'], function () {
            Route::post('', [TrimsStoreBinCardController::class, 'store']);
            Route::get('{binCard}/edit', [TrimsStoreBinCardController::class, 'edit']);
            Route::put('{binCard}', [TrimsStoreBinCardController::class, 'update']);
            Route::delete('{binCard}', [TrimsStoreBinCardController::class, 'destroy']);
            Route::get('fetch-bin-card-no/{bookingId}', [TrimsStoreBinCardNoApiController::class, '__invoke']);
            Route::get('fetch-mrr-date/{mrr}', [TrimsStoreMrrDateApiController::class, '__invoke']);
            Route::get('fetch-bin-no', [TrimsStoreBinNoApiController::class, '__invoke']);
            Route::get('fetch-inventory/{bookingId}', [BookingWiseInventoryController::class, '__invoke']);

            Route::group(['prefix' => 'details'], function () {
                Route::get('{binCard}', [TrimsBinCardDetailsController::class, 'getDetails']);
                Route::put('{detail}', [TrimsBinCardDetailsController::class, 'update']);
                Route::delete('{detail}', [TrimsBinCardDetailsController::class, 'destroy']);
            });
        });

        Route::group(['prefix' => 'issue'], function () {
            Route::post('', [TrimsStoreIssueController::class, 'store']);
            Route::get('{issue}/edit', [TrimsStoreIssueController::class, 'edit']);
            Route::put('{issue}', [TrimsStoreIssueController::class, 'update']);
            Route::delete('/{issue}', [TrimsStoreIssueController::class, 'destroy']);
            Route::get('fetch-bin-card/{binCard}', [TrimsBinCardApiController::class, '__invoke']);

            Route::group(['prefix' => 'details'], function () {
                Route::get('{issue}', [TrimsStoreIssueDetailsController::class, 'getDetails']);
                Route::put('{detail}', [TrimsStoreIssueDetailsController::class, 'update']);
                Route::delete('{detail}', [TrimsStoreIssueDetailsController::class, 'destroy']);
            });
        });

        Route::group(['prefix' => 'delivery-challan'], function () {
            Route::post('', [TrimsStoreDeliveryChallanController::class, 'store']);
            Route::get('{challanNo}/edit', [TrimsStoreDeliveryChallanController::class, 'edit']);
            Route::put('{challan}', [TrimsStoreDeliveryChallanController::class, 'update']);
            Route::delete('/{challanNo}', [TrimsStoreDeliveryChallanController::class, 'destroy']);
            Route::get('/booking-no/{challan}', [DeliveryChallanWiseBookingNoApiController::class, '__invoke']);

            Route::group(['prefix' => 'details'], function () {
                Route::put('{detail}', [TrimsStoreDeliveryChallanDetailsController::class, 'update']);
                Route::delete('{detail}', [TrimsStoreDeliveryChallanDetailsController::class, 'destroy']);
            });
        });

    });
});

Route::group(['prefix' => 'common-api'], function () {
    Route::get('fetch-fabric-receive-batch-no', [CommonApiController::class, 'fabricReceiveBatchNo']);
    Route::get('fabric-store-variable-setting-data', FabricStoreVariableSettingApiController::class);
});
