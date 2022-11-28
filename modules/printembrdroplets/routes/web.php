<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Printembrdroplets\Controllers\ArchivedGatePassController;
use SkylarkSoft\GoRMG\Printembrdroplets\Controllers\ColorAndSizePrintFactoryReportController;
use SkylarkSoft\GoRMG\Printembrdroplets\Controllers\ColorAndSizeReportController;
use SkylarkSoft\GoRMG\Printembrdroplets\Controllers\DailyPrintEmbrReportController;
use SkylarkSoft\GoRMG\Printembrdroplets\Controllers\DashboardDataController;
use SkylarkSoft\GoRMG\Printembrdroplets\Controllers\GatePassController;
use SkylarkSoft\GoRMG\Printembrdroplets\Controllers\PrintController;
use SkylarkSoft\GoRMG\Printembrdroplets\Controllers\PrintEmbrProductionController;
use SkylarkSoft\GoRMG\Printembrdroplets\Controllers\PrintEmbrQcController;
use SkylarkSoft\GoRMG\Printembrdroplets\Controllers\PrintEmbrQcDeliveryChallanController;
use SkylarkSoft\GoRMG\Printembrdroplets\Controllers\PrintEmbrTargetController;
use SkylarkSoft\GoRMG\Printembrdroplets\Controllers\PrintFactoryChallanController;
use SkylarkSoft\GoRMG\Printembrdroplets\Controllers\PrintFactoryProductionRejectionScanController;
use SkylarkSoft\GoRMG\Printembrdroplets\Controllers\PrintFactoryQcRejectionScanController;
use SkylarkSoft\GoRMG\Printembrdroplets\Controllers\PrintFactoryReceiveController;

Route::middleware(['web', 'auth', 'menu-auth'])->group(function () {

    Route::get('/print-send-scan', [PrintController::class, 'printInventoryScan']);
    Route::post('/print-send-scan-post', [PrintController::class, 'printScanPost']);

    Route::get('/send-to-print/{challan_no}', [GatePassController::class, 'sendToPrintFactory']);
    Route::post('/send-to-print-post', [GatePassController::class, 'sendToPrint']);
    Route::get('/view-print-getapass/{challan_no}', [GatePassController::class, 'viewPrintGetpass']);
    Route::get('/gatepasses', [GatePassController::class, 'index']);
    Route::delete('/gatepasses/{id}', [GatePassController::class, 'destroy']);
    Route::get('search-gatepass-list', [GatePassController::class, 'searchGatepassList']);
    Route::get('/get-challan-wise-bundle-list/{challan_no}', [GatePassController::class, 'viewChallanWiseBundleList']);
    Route::get('/get-challan-wise-deleted-bundle-list/{challan_no}', [GatePassController::class, 'viewChallanWiseDeletedBundleList']);
    Route::delete('/delete-print-invntory-bundle/{id}', [GatePassController::class, 'deletePrintInventoryBundle']);
    Route::get('/get-security-status/{challan_id}', [GatePassController::class, 'getSecurityGatepass']);
    Route::post('/get-security-status-post', [GatePassController::class, 'getSecurityGatepassPost']);

    Route::get('/archived-gatepasses', [ArchivedGatePassController::class, 'index']);
    Route::get('/search-archived-gatepass-list', [ArchivedGatePassController::class, 'search']);
    Route::get('/get-archived-challan-wise-bundle-list/{challan_no}', [ArchivedGatePassController::class, 'viewChallanWiseBundleList']);
    Route::get('/view-archived-print-getapass/{challan_no}', [ArchivedGatePassController::class, 'viewPrintGetpass']);


    Route::get('/print-rejection', [PrintController::class, 'printRejection']);
    Route::post('/print-rejection-post', [PrintController::class, 'printRejectionPost']);

    // Report route
    Route::get('/buyer-wise-print-send-receive-report', [ColorAndSizeReportController::class, 'getBuyerWiseSendReceivedForm']);
    Route::get('/get-buyer-print-send-receive-report', [ColorAndSizeReportController::class, 'getBuyerWiseSendReceivedPost']);
    Route::get('/buyer-wise-print-send-receive-report-download/{type}/{buyer_id}/{page}', [ColorAndSizeReportController::class, 'getBuyerWisePrintSendReceiveDownload']);

    Route::get('/order-wise-print-send-receive-report', [ColorAndSizeReportController::class, 'getOrderWisePrintReport']);
    Route::get('/get-style-wise-print-send-receive-report/{style_id}', [ColorAndSizeReportController::class, 'getStyleWisePrintSendReceiveReport']);
    Route::get('/get-order-wise-print-send-receive-report/{buyer_id}/{order_id}', [ColorAndSizeReportController::class, 'getOrderWisePrintReportData']);
    Route::get('/order-wise-print-send-receive-report-download/{type}/{buyer_id}/{order_id}', [ColorAndSizeReportController::class, 'getOrderWisePrintReportDownload']);
    Route::get('/style-wise-print-send-receive-report-download/{type}/{buyer_id}/{style_id}', [ColorAndSizeReportController::class, 'getStyleWisePrintReportDownload']);

    Route::get('/cutting-no-wise-color-print-send-receive-report', [ColorAndSizeReportController::class, 'getCuttingNoWisePrintReport']);
    Route::get('/cutting-no-wise-color-print-send-receive-report-post/{buyer_id}/{order_id}/{color_id}/{cutting_no}', [ColorAndSizeReportController::class, 'getCuttingNoWisePrintReportPost']);
    Route::get('/cutting-no-wise-print-send-receive-report-download/{type}/{buyer_id}/{order_id}/{color_id}/{cutting_no}', [ColorAndSizeReportController::class, 'getCuttingNoWisePrintReportDownload']);

    Route::match(['GET', 'POST'],'/date-wise-print-send-report', [ColorAndSizeReportController::class, 'getDateWisePrintReport']);
    Route::post('/date-wise-print-send-report-post', [ColorAndSizeReportController::class, 'getMothWisePrintReportPost']);
    Route::get('/date-wise-print-send-report-download', [ColorAndSizeReportController::class, 'getDateWisePrintReportDownload']);

    // Print Factory rcv, production, delivery report
    Route::match(['GET', 'POST'],'/date-wise-print-rcv-production-delivery-report', [ColorAndSizePrintFactoryReportController::class, 'getDateWisePrintReport']);
    Route::get('/date-wise-print-rcv-production-delivery-report-download', [ColorAndSizePrintFactoryReportController::class, 'getDateWisePrintReportDownload']);

    // print, embroidary, input & output dashboard data
    Route::get('get-dashboard-related-data', [DashboardDataController::class, 'getDashboardRelatedData']);
    Route::get('get-all-rejection-data', [DashboardDataController::class, 'getAllRejectionData']);

    /*
     * Print factory receive
     */
    Route::get('print-factory-receive-challan-list', [PrintFactoryChallanController::class, 'index']);
    Route::get('print-factory-receive-tag-list', [PrintFactoryChallanController::class, 'index']);
    Route::get('receive-challan/{challan_no}/edit', [PrintFactoryChallanController::class, 'edit']);
    Route::post('receive-challan/{challan_no}', [PrintFactoryChallanController::class, 'update']);
    Route::get('receive-challan-tag/{challan_no}/view', [PrintFactoryChallanController::class, 'viewPrintRcvChallan']);
    Route::delete('receive-challan/{challan_no}', [PrintFactoryChallanController::class, 'deleteChallan']);

    Route::get('receive-challan-wise-bundle/{challan_no}', [PrintFactoryChallanController::class, 'challanWiseBundle']);
    Route::delete('/delete-print-receive-invntory-bundle/{id}', [PrintFactoryChallanController::class, 'deletePrintInventoryBundle']);

    Route::get('print-embr-factory-receive-scan', [PrintFactoryReceiveController::class, 'scanPage']);
    Route::post('print-embr-factory-receive-scan-post', [PrintFactoryReceiveController::class, 'scanPost']);
    Route::get('print-embr-factory-rcv-rejection', [PrintFactoryReceiveController::class, 'rejectionForm']);
    Route::post('print-embr-factory-rcv-rejection-post', [PrintFactoryReceiveController::class, 'rejectionPost']);

    Route::get('print-embr-factory-production-rejection', [PrintFactoryProductionRejectionScanController::class, 'rejectionForm']);
    Route::post('print-factory-production-rejection-post', [PrintFactoryProductionRejectionScanController::class, 'rejectionFormPost']);

    Route::get('print-embr-factory-qc-rejection', [PrintFactoryQcRejectionScanController::class, 'rejectionForm']);
    Route::post('print-factory-qc-rejection-post', [PrintFactoryQcRejectionScanController::class, 'rejectionFormPost']);

    Route::get('create-factory-receive-challan/{challan_no}', [PrintFactoryChallanController::class, 'createFactoryReceivedChallan']);
    Route::post('create-factory-receive-challan-post', [PrintFactoryChallanController::class, 'createFactoryReceiveChallanPost']);

    Route::get('create-factory-received-tag/{challan_no}', [PrintFactoryChallanController::class, 'createFactoryReceiveTag']);
    Route::get('create-received-challan-form-tag', [PrintFactoryChallanController::class, 'createReceivedChallanFromTag']);
    Route::post('create-received-challan-from-tag-post', [PrintFactoryChallanController::class, 'createReceivedChallanFromTagPost']);

    Route::get('print-embr-factory-receive-challan-list', [PrintFactoryChallanController::class, 'index']);
    Route::get('print-embr-factory-receive-tag-list', [PrintFactoryChallanController::class, 'index']);

    /*
     * Print/Embr Factory production Scan Point
     */
    Route::get('print-embr-production-scan', [PrintEmbrProductionController::class, 'printEmbrProductionScan']);
    Route::post('print-embr-production-scan-post', [PrintEmbrProductionController::class, 'printEmbrProductionScanPost']);
    Route::get('close-print-embr-production-challan/{production_challan_no}', [PrintEmbrProductionController::class, 'closePrintProductionChallan']);

    /*
     * Print/Embr QC Scan Point
     */
    Route::get('print-embr-qc-scan', [PrintEmbrQcController::class, 'printEmbrQcScan']);
    Route::post('print-embr-qc-scan-post', [PrintEmbrQcController::class, 'printEmbrQcScanPost']);
    Route::get('create-print-embr-qc-tag/{challan_no}', [PrintEmbrQcController::class, 'createPrintEmbrQcTag']);
    Route::get('create-print-embr-delivery-challan/{challan_no}', [PrintEmbrQcController::class, 'createPrintEmbrDeliveryChallan']);
    Route::post('create-print-factory-delivery-challan-post', [PrintEmbrQcController::class, 'createPrintEmbrDeliveryChallanPost']);
    Route::get('/view-qc-or-delivery-challan', [PrintEmbrQcDeliveryChallanController::class, 'qcDeliveryChallanOrTag']);

    Route::post('create-print-embr-qc-challan', [PrintEmbrQcController::class, 'createPrintEmbrQcChallan']);
    Route::get('/print-embr-qc-tag-list', [PrintEmbrQcDeliveryChallanController::class, 'index']);
    Route::get('/print-embr-delivery-challan-list', [PrintEmbrQcDeliveryChallanController::class, 'index']);
    Route::delete('delete-delivery-tag-challan/{id}', [PrintEmbrQcDeliveryChallanController::class, 'deleteDeliveryTagChallan']);
    Route::get('create-delivery-challan-from-tag', [PrintEmbrQcDeliveryChallanController::class, 'createDeliveryChallanFromTag']);
    Route::post('create-delivery-challan-from-tag-post', [PrintEmbrQcDeliveryChallanController::class, 'createDeliveryChallanFromTagPost']);
    Route::get('view-challan-or-tag', [PrintEmbrQcDeliveryChallanController::class, 'qcDeliveryChallanOrTag']);

    Route::get('print-embroidery-target', [PrintEmbrTargetController::class, 'printEmbroideryTarget']);
    Route::post('print-embroidery-target-post', [PrintEmbrTargetController::class, 'printEmbroideryTargetPost']);

    Route::get('daily-print-embr-report', [DailyPrintEmbrReportController::class, 'getReport']);
    Route::get('daily-print-embr-report-download', [DailyPrintEmbrReportController::class, 'downloadReport']);

});
