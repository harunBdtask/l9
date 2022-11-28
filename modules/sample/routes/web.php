<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Sample\Controllers\SampleCommonAPIController;
use SkylarkSoft\GoRMG\Sample\Controllers\SampleOrderAccessoriesDetailsController;
use SkylarkSoft\GoRMG\Sample\Controllers\SampleOrderFabricDetailsController;
use SkylarkSoft\GoRMG\Sample\Controllers\SampleOrderRequisitionController;
use SkylarkSoft\GoRMG\Sample\Controllers\SampleOrderRequisitionDetailsController;
use SkylarkSoft\GoRMG\Sample\Controllers\SampleProcessingController;
use SkylarkSoft\GoRMG\Sample\Controllers\SampleProductionController;
use SkylarkSoft\GoRMG\Sample\Controllers\SampleTNAController;
use SkylarkSoft\GoRMG\Sample\Controllers\SampleTrimsIssueController;
use SkylarkSoft\GoRMG\Sample\Controllers\SampleTrimsReceiveController;

Route::group(['middleware' => ['web', 'auth', 'menu-auth']], function () {
    Route::group(['prefix' => 'sample-management'], function () {
        Route::group(['prefix' => 'order-requisition'], function () {
            Route::get('/list', [SampleOrderRequisitionController::class, 'index']);
            Route::get('/form/{any?}', [SampleOrderRequisitionController::class, 'create'])->where('any', '.*');
            Route::post('save', [SampleOrderRequisitionController::class, 'save']);
            Route::post('create-update', [SampleOrderRequisitionController::class, 'createOrUpdate']);
            Route::get('/{sampleOrderRequisition}', [SampleOrderRequisitionController::class, 'show']);
            Route::delete('delete/{sampleOrderRequisition}', [SampleOrderRequisitionController::class, 'delete']);
            Route::get('view/{sampleOrderRequisition}', [SampleOrderRequisitionController::class, 'view']);
            Route::get('pdf/{sampleOrderRequisition}', [SampleOrderRequisitionController::class, 'pdf']);
            Route::get('excel/{sampleOrderRequisition}', [SampleOrderRequisitionController::class, 'excel']);
            Route::get('fabric-booking-view/{sampleOrderRequisition}', [SampleOrderRequisitionController::class, 'sampleFabBookingview']);
        });
        Route::group(['prefix' => 'order-requisition-details'], function () {
            Route::post('store/{sampleOrderRequisition}', [SampleOrderRequisitionDetailsController::class, 'store']);
            Route::post('delete/{sampleOrderRequisitionDetails}', [SampleOrderRequisitionDetailsController::class, 'delete']);
        });
        Route::group(['prefix' => 'order-requisition-fabric-details'], function () {
            Route::post('store/{sampleOrderRequisition}', [SampleOrderFabricDetailsController::class, 'store']);
            Route::post('delete/{sampleOrderFabricDetails}', [SampleOrderFabricDetailsController::class, 'delete']);
        });
        Route::group(['prefix' => 'order-requisition-accerssories-details'], function () {
            Route::post('store/{sampleOrderRequisition}', [SampleOrderAccessoriesDetailsController::class, 'store']);
            Route::post('delete/{sampleOrderAccessoriesDetails}', [SampleOrderAccessoriesDetailsController::class, 'delete']);
        });
        Route::group(['prefix' => 'sample-tna'], function () {
            Route::get('/list', [SampleTNAController::class, 'index']);
            Route::get('/form/{any?}', [SampleTNAController::class, 'create'])->where('any', '.*');
            Route::post('save', [SampleTNAController::class, 'save']);
            Route::delete('delete/{sampleTNA}', [SampleTNAController::class, 'delete']);
            Route::get('show/{sampleTNA}', [SampleTNAController::class, 'show']);
            Route::get('view/{sampleTNA}', [SampleTNAController::class, 'view']);
            Route::get('pdf/{sampleTNA}', [SampleTNAController::class, 'pdf']);
            Route::get('excel/{sampleTNA}', [SampleTNAController::class, 'excel']);
        });
        Route::group(['prefix' => 'sample-processing'], function () {
            Route::get('/list', [SampleProcessingController::class, 'index']);
            Route::get('/form/{any?}', [SampleProcessingController::class, 'create'])->where('any', '.*');
            Route::post('create-update', [SampleProcessingController::class, 'createOrUpdate']);
            Route::delete('delete/{sampleProcessing}', [SampleProcessingController::class, 'delete']);
            Route::post('delete-details/{sampleProcessingDetails}', [SampleProcessingController::class, 'deleteDetails']);
            Route::get('show/{sampleProcessing}', [SampleProcessingController::class, 'show']);
            Route::get('view/{sampleProcessing}', [SampleProcessingController::class, 'view']);
            Route::get('pdf/{sampleProcessing}', [SampleProcessingController::class, 'pdf']);
            Route::get('excel/{sampleProcessing}', [SampleProcessingController::class, 'excel']);
        });
        Route::group(['prefix' => 'sample-production'], function () {
            Route::post('store/{sampleProcessing}', [SampleProductionController::class, 'store']);
            Route::post('delete/{sampleProductionDetails}', [SampleProductionController::class, 'delete']);
        });
        Route::group(['prefix' => 'trims-issue'], function () {
            Route::get('/list', [SampleTrimsIssueController::class, 'index']);
            Route::get('/form/{any?}', [SampleTrimsIssueController::class, 'create'])->where('any', '.*');
            Route::post('save', [SampleTrimsIssueController::class, 'save']);
            Route::delete('delete/{sampleTrimsIssue}', [SampleTrimsIssueController::class, 'delete']);
            Route::get('show/{sampleTrimsIssue}', [SampleTrimsIssueController::class, 'show']);
            Route::get('view/{sampleTrimsIssue}', [SampleTrimsIssueController::class, 'view']);
            Route::get('pdf/{sampleTrimsIssue}', [SampleTrimsIssueController::class, 'pdf']);
            Route::get('excel/{sampleTrimsIssue}', [SampleTrimsIssueController::class, 'excel']);
        });
        Route::group(['prefix' => 'trims-receive'], function () {
            Route::get('/list', [SampleTrimsReceiveController::class, 'index']);
            Route::get('/form/{any?}', [SampleTrimsReceiveController::class, 'create'])->where('any', '.*');
            Route::post('save', [SampleTrimsReceiveController::class, 'save']);
            Route::delete('delete/{sampleTrimsReceive}', [SampleTrimsReceiveController::class, 'delete']);
            Route::post('delete-details/{sampleTrimsReceiveDetails}', [SampleTrimsReceiveController::class, 'deleteDetails']);
            Route::get('show/{sampleTrimsReceive}', [SampleTrimsReceiveController::class, 'show']);
            Route::get('view/{sampleTrimsReceive}', [SampleTrimsReceiveController::class, 'view']);
            Route::get('pdf/{sampleTrimsReceive}', [SampleTrimsReceiveController::class, 'pdf']);
            Route::get('excel/{sampleTrimsReceive}', [SampleTrimsReceiveController::class, 'excel']);
        });
        Route::group(['prefix' => 'common-api/v1/'], function () {
            Route::get('styles-search', [SampleCommonAPIController::class, 'stylesSearch']);
            Route::get('repeat-styles-search', [SampleCommonAPIController::class, 'repeatStylesSearch']);
            Route::get('main/{sampleOrderRequisition}', [SampleCommonAPIController::class, 'main']);
            Route::get('details/{sampleOrderRequisition}', [SampleCommonAPIController::class, 'details']);
            Route::get('fabrics/{sampleOrderRequisition}', [SampleCommonAPIController::class, 'fabrics']);
            Route::get('fabric-details/{sampleOrderRequisition}', [SampleCommonAPIController::class, 'fabricDetails']);
            Route::get('accessories/{sampleOrderRequisition}', [SampleCommonAPIController::class, 'accessories']);
            Route::get('qty-form-data', [SampleCommonAPIController::class, 'qtyFormData']);
            Route::get('gmts-sizes', [SampleCommonAPIController::class, 'gmtsSizes']);
            Route::get('fabric-natures', [SampleCommonAPIController::class, 'fabricNatures']);
            Route::get('users', [SampleCommonAPIController::class, 'users']);
            Route::get('user-team', [SampleCommonAPIController::class, 'userTeamInfo']);
            Route::get('buying-agent-merchants', [SampleCommonAPIController::class, 'buyingAgentMerchants']);
            Route::get('fabric-costing-from-budget', [SampleCommonAPIController::class, 'fabricCostingFromBudget']);
            Route::get('trim-costing-from-budget', [SampleCommonAPIController::class, 'trimCostingFromBudget']);
            Route::get('buyers', [SampleCommonAPIController::class, 'buyers']);
            Route::get('samples', [SampleCommonAPIController::class, 'samples']);
            Route::get('sample', [SampleCommonAPIController::class, 'sample']);
            Route::get('tna/{sampleTNA}', [SampleCommonAPIController::class, 'tna']);
            Route::get('sample-templates', [SampleCommonAPIController::class, 'tnaTemplates']);
            Route::get('process-from-tna-template/{sampleTemplate}', [SampleCommonAPIController::class, 'processFromTNATemplate']);
            Route::get('process-from-sample/{sampleOrderRequisition}', [SampleCommonAPIController::class, 'processFromSample']);
            Route::get('processing/{sampleProcessing}', [SampleCommonAPIController::class, 'processing']);
            Route::get('processing-details/{sampleProcessing}', [SampleCommonAPIController::class, 'processingDetails']);
            Route::get('production/{sampleProcessing}', [SampleCommonAPIController::class, 'production']);
            Route::get('production-details/{sampleProcessing}', [SampleCommonAPIController::class, 'productionDetails']);
            Route::get('issue-basis', [SampleCommonAPIController::class, 'issueBasis']);
            Route::get('process-from-sample-accessories/{sampleOrderRequisition}', [SampleCommonAPIController::class, 'processFromSampleAccessories']);
            Route::get('trims-issue/{sampleTrimsIssue}', [SampleCommonAPIController::class, 'trimsIssue']);
            Route::get('trims-issue-details/{sampleTrimsIssue}', [SampleCommonAPIController::class, 'trimsIssueDetails']);
            Route::get('trims-issues', [SampleCommonAPIController::class, 'trimsIssues']);
            Route::get('process-from-trims-issue/{sampleTrimsIssue}', [SampleCommonAPIController::class, 'processFromSmpTrimsIssue']);
            Route::get('trims-receive-info/{sampleTrimsReceive}', [SampleCommonAPIController::class, 'trimsReceive']);
            Route::get('trims-receive-details/{sampleTrimsReceive}', [SampleCommonAPIController::class, 'trimsReceiveDetails']);
        });

        Route::get('/sample-info-processing-entry', [SampleOrderRequisitionController::class, 'index']);
        Route::group(['prefix' => 'sample-info'], function () {
            Route::get('/sample-info-processing-entry', [SampleOrderRequisitionController::class, 'index']);
        });
    });

    Route::get('/sample-order-fabric-booking', [SampleOrderRequisitionController::class, 'index']);
    Route::get('/sample-order-trims-booking', [SampleOrderRequisitionController::class, 'index']);
    Route::get('/sample-info-tna', [SampleOrderRequisitionController::class, 'index']);
    Route::get('/sample-info-processing-entry', [SampleOrderRequisitionController::class, 'index']);
    Route::get('/sample-info-processing-entry-report', [SampleOrderRequisitionController::class, 'index']);
});
