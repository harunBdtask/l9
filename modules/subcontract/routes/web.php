<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries\ColorRangesController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries\ColorsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries\DyeingMachinesController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries\DyeingOperationFunctionsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries\DyeingRecipeOperationsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries\DyesItemsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries\FabricConstructionTypeController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries\FactoryWiseVariableSettingController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries\OrderDefaultSelectController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries\ShiftsApiController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries\SubDyeingUnitsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries\SubTextileOperationSearchController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries\SubTextileProcessSearchController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs\BatchBuyerRateController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs\CountAndFabricTypeApiController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs\FabricCompositionApiController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs\FactoryWiseLocationApiController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs\SubDyeingBatchApiController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs\SubTextileGreyStoreApiController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs\SubTextileOrderApiController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs\SubTextileOrderWiseSubDyeingBatchApiController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs\SubTextilePartiesSearchController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs\SupplierWiseSubTextileOrdersController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Compactor\CompactorController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Compactor\CompactorSearchBatchOrOrderDetailsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Dryer\SubDryerController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Dryer\SubDryerSearchBatchOrOrderDetailsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\BatchCreateController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\BatchCreateDetailsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\BatchMachineController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\DyeingRecipe\BatchRecipeMachineController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\DyeingRecipe\BatchSearchController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\DyeingRecipe\BatchWiseRecipeSearchController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\DyeingRecipe\MultipleRecipeDownloadController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\DyeingRecipe\RecipeController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\DyeingRecipe\RecipeDetailsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\DyeingRecipe\SubDyeingRecipeRequisitionController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\OrderDetailsFilterController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\SubDyeingHtSet\SubDyeingHtSetController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\SubDyeingHtSet\SubDyeingHtSetDetailSearchController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\SubDyeingSqueezer\SubDyeingSqueezerController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\SubDyeingSqueezer\SubDyeingSqueezerDetailSearchController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\SubDyeingTubeCompacting\SubDyeingTubeCompactingController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\SubDyeingTubeCompacting\SubDyeingTubeCompactingDetailSearchController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProduction\DyeingProductionController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries\DyeingFloorController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries\DyeingMachineController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries\SubContractGreyStoreController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries\SubDyeingOperationFunctionController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries\SubDyeingRecipeOperationController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries\SubDyeingUnitController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries\SubDyeingVariableController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries\SubTextileProcessController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Reports\BatchReportController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Reports\DateWiseDeliveryReportController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Reports\DyeingLedgerReportController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Reports\DyeingProductionDailyReportController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Reports\GreyFabricStockSummaryReportController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Reports\OrderProfitLossController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Reports\OrderReportController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Reports\OrderWiseStockReportController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Reports\PartyAndOrderWiseReportController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Reports\SubDyeingBatchCostingReportController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Reports\SubDyeingDailyProductionReportController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Reports\SubDyeingDyesChemicalStoreStatementController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Reports\SubDyeingFinishingProductionDailyReportController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Reports\SubGreyStoreStockSummeryReportController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Slitting\SlittingController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Slitting\SubSlittingSearchBatchOrOrderDetailsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Stenter\SubDyeingStenterController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Stenter\SubDyeingStenterSearchBatchOrOrderDetailController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubDyeingFinishingProduction\SearchBatchOrOrderDetailsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubDyeingFinishingProduction\SubDyeingFinishingProductionController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubDyeingGoodsDelivery\SubDyeingGoodsDeliveryController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubDyeingGoodsDelivery\SubDyeingGoodsDeliverySearchController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubDyeingPeach\SearchForPeachDetailsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubDyeingPeach\SubDyeingPeachController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubInboundBilling\SubInboundBillingController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric\FabricIssueDownloadAbleController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric\FabricReceiveDownloadAbleController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric\MaterialFabricBarcodeController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric\MaterialFabricIssueController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric\MaterialFabricIssueDetailsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric\MaterialFabricReceiveController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric\MaterialFabricReceiveDetailsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric\MaterialFabricTransferController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric\MaterialFabricTransferDetailsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric\SearchMaterialTransferDetailsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\SubTextileOrderController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\SubTextileOrderDetailController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\SubTextileOrderDownloadController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Tumble\SearchForTumbleDetailsController;
use SkylarkSoft\GoRMG\Subcontract\Controllers\Tumble\SubDyeingTumbleController;

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'subcontract'], function () {
    Route::resource('sub-dyeing-unit', SubDyeingUnitController::class, [
        'names' => [
            'index' => 'sub-dyeing-unit.index',
            'store' => 'sub-dyeing-unit.store',
            'update' => 'sub-dyeing-unit.update',
            'edit' => 'sub-dyeing-unit.edit',
            'destroy' => 'sub-dyeing-unit.destroy',
        ],
    ]);

    // Textile Process Routes
    Route::group(['prefix' => 'process'], function () {
        Route::get('/', [SubTextileProcessController::class, 'index']);
        Route::post('/', [SubTextileProcessController::class, 'storeAndUpdate']);
        Route::get('/{process}', [SubTextileProcessController::class, 'edit']);
        Route::get('/{process}/status', [SubTextileProcessController::class, 'status']);
    });

    // Textile Subcontract Order Management Routes
    Route::group(['prefix' => 'textile-orders'], function () {
        Route::get('/', [SubTextileOrderController::class, 'index']);
        Route::post('/store', [SubTextileOrderController::class, 'store']);
        Route::put('/{subTextileOrder}', [SubTextileOrderController::class, 'update']);
        Route::delete('/{subTextileOrder}', [SubTextileOrderController::class, 'destroy']);
        Route::get('/{subTextileOrder}/edit', [SubTextileOrderController::class, 'edit']);
        Route::get('/{any?}', [SubTextileOrderController::class, 'form'])->where('any', '.*');
    });
    Route::get('view/{subTextileOrder}', [SubTextileOrderDownloadController::class, 'view']);
    Route::get('pdf/{subTextileOrder}', [SubTextileOrderDownloadController::class, 'pdf']);

    // Textile Subcontract Order Management Detail Routes
    Route::group(['prefix' => 'textile-order-details'], function () {
        Route::get('/{subTextileOrderId}', [SubTextileOrderDetailController::class, 'index']);
        Route::post('/store', [SubTextileOrderDetailController::class, 'store']);
        Route::put('/{subTextileOrderDetail}', [SubTextileOrderDetailController::class, 'update']);
        Route::delete('/{subTextileOrderDetail}', [SubTextileOrderDetailController::class, 'destroy']);
        Route::post('/sync-receive-issue', [SubTextileOrderDetailController::class, 'syncData']);

        Route::get('/form/{any?}', [SubTextileOrderDetailController::class, 'form'])->where('any', '.*');
    });

    // Textile Subcontract Fabric Material Receive
    Route::group(['prefix' => 'material-fabric-receive'], function () {
        Route::get('/', [MaterialFabricReceiveController::class, 'index']);
        Route::post('/store', [MaterialFabricReceiveController::class, 'store']);
        Route::put('/{subGreyStoreReceive}', [MaterialFabricReceiveController::class, 'update']);
        Route::delete('/{subGreyStoreReceive}', [MaterialFabricReceiveController::class, 'destroy']);
        Route::get('/{subGreyStoreReceive}/edit', [MaterialFabricReceiveController::class, 'edit']);
        Route::get('view/{greyStoreIssue}', [FabricReceiveDownloadAbleController::class, 'view']);
        Route::get('pdf/{greyStoreIssue}', [FabricReceiveDownloadAbleController::class, 'pdf']);

        Route::group(['prefix' => '/details'], function () {
            Route::get('/{subGreyStoreReceive}', [MaterialFabricReceiveDetailsController::class, 'getDetails']);
            Route::get('/challanDetails/{subGreyStoreReceive}', [MaterialFabricReceiveDetailsController::class, 'getChallanDetails']);
            Route::post('/store', [MaterialFabricReceiveDetailsController::class, 'store']);
            Route::put('/{subGreyStoreReceiveDetails}', [MaterialFabricReceiveDetailsController::class, 'update']);
            Route::delete('/{subGreyStoreReceiveDetails}', [MaterialFabricReceiveDetailsController::class, 'destroy']);
        });

        // BARCODE
        Route::group(['prefix' => 'barcode'], function () {
            Route::get('/create/{receive}', [MaterialFabricBarcodeController::class, 'create']);
            Route::post('/generate-barcodes', [MaterialFabricBarcodeController::class, 'store']);
            Route::get('view/{receive}', [MaterialFabricBarcodeController::class, 'view']);
            Route::get('print/{detail}', [MaterialFabricBarcodeController::class, 'print']);
        });

        Route::get('/{any?}', [MaterialFabricReceiveController::class, 'create'])->where('any', '.*');
    });


    // Textile Subcontract Fabric Material issue
    Route::group(['prefix' => 'material-fabric-issue'], function () {
        Route::get('/', [MaterialFabricIssueController::class, 'index']);
        Route::post('/store', [MaterialFabricIssueController::class, 'store']);
        Route::put('/{greyStoreIssue}', [MaterialFabricIssueController::class, 'update']);
        Route::delete('/{greyStoreIssue}', [MaterialFabricIssueController::class, 'destroy']);
        Route::get('/{greyStoreIssue}/edit', [MaterialFabricIssueController::class, 'edit']);
        Route::get('view/{greyStoreIssue}', [FabricIssueDownloadAbleController::class, 'view']);
        Route::get('pdf/{greyStoreIssue}', [FabricIssueDownloadAbleController::class, 'pdf']);

        Route::group(['prefix' => '/details'], function () {
            Route::get('/{greyStoreIssue}', [MaterialFabricIssueDetailsController::class, 'getDetails']);
            Route::put('/{subGreyStoreIssueDetail}', [MaterialFabricIssueDetailsController::class, 'update']);
        });

        Route::get('/{any?}', [MaterialFabricIssueController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => 'material-fabric-transfer'], function () {
        Route::get('', [MaterialFabricTransferController::class, 'index']);
        Route::post('', [MaterialFabricTransferController::class, 'store']);
        Route::get('{transfer}/edit', [MaterialFabricTransferController::class, 'edit']);
        Route::get('get-details/{order_no}', [MaterialFabricTransferController::class, 'getDetails']);
        Route::put('{transfer}/update', [MaterialFabricTransferController::class, 'update']);
        Route::delete('{transfer}', [MaterialFabricTransferController::class, 'delete']);
        Route::get('/view/{id}', [MaterialFabricTransferController::class, 'view']);
        Route::get('/pdf/{id}', [MaterialFabricTransferController::class, 'pdf']);

        Route::group(['prefix' => '/details'], function () {
            Route::get('fetch-order-wise-fabric-details', SearchMaterialTransferDetailsController::class);
            Route::get('{transfer}', [MaterialFabricTransferDetailsController::class, 'getDetails']);
            Route::post('{transfer}', [MaterialFabricTransferDetailsController::class, 'store']);
            Route::get('{detail}/edit', [MaterialFabricTransferDetailsController::class, 'edit']);
            Route::put('{detail}', [MaterialFabricTransferDetailsController::class, 'update']);
            Route::delete('{detail}', [MaterialFabricTransferDetailsController::class, 'destroy']);
        });

        Route::get('/{any?}', [MaterialFabricTransferController::class, 'create'])->where('any', '.*');
    });

    // Textile Subcontract Batch Create Routes
    Route::group(['prefix' => 'dyeing-process'], function () {
        Route::group(['prefix' => 'batch-entry'], function () {
            Route::get('/', [BatchCreateController::class, 'index']);
            Route::get('/{dyeingBatch}/edit', [BatchCreateController::class, 'edit']);
            Route::post('/store', [BatchCreateController::class, 'store']);
            Route::put('/{dyeingBatch}', [BatchCreateController::class, 'update']);
            Route::delete('/{dyeingBatch}', [BatchCreateController::class, 'destroy']);
            Route::get('/view/{dyeingBatch}', [BatchCreateController::class, 'view']);
            Route::get('/pdf/{dyeingBatch}', [BatchCreateController::class, 'pdf']);

            Route::group(['prefix' => 'details'], function () {
                Route::get('/{dyeingBatch}', [BatchCreateDetailsController::class, 'getDetails']);
                Route::post('/{dyeingBatch}', [BatchCreateDetailsController::class, 'store']);
                Route::put('/{dyeingBatch}/{batchDetail}', [BatchCreateDetailsController::class, 'update']);
                Route::delete('/{dyeingBatch}/{batchDetail}', [BatchCreateDetailsController::class, 'destroy']);
            });

            Route::group(['prefix' => 'machine'], function () {
                Route::get('/{dyeingBatch}', [BatchMachineController::class, 'index']);
                Route::post('/{dyeingBatch}', [BatchMachineController::class, 'store']);
                Route::delete('/{dyeingBatch}/{batchMachineAllocation}', [BatchMachineController::class, 'destroy']);
            });

            Route::group(['prefix' => 'buyer-rate'], function () {
                Route::get('{dyeingBatch}', [BatchBuyerRateController::class, 'show']);
                Route::patch('{dyeingBatch}', [BatchBuyerRateController::class, 'update']);
            });

            Route::get('/{any?}', [BatchCreateController::class, 'create'])->where('any', '.*');
        });

        Route::group(['prefix' => '/recipe-entry'], function () {
            Route::get('/', [RecipeController::class, 'index']);
            Route::post('', [RecipeController::class, 'store']);
            Route::get('/{dyeingRecipe}/edit', [RecipeController::class, 'edit']);
            Route::put('/{dyeingRecipe}', [RecipeController::class, 'update']);
            Route::delete('/{dyeingRecipe}', [RecipeController::class, 'destroy']);
            Route::get('/copy-previous-recipe-details/{batchId}', BatchWiseRecipeSearchController::class);

            Route::get('/view/{dyeingRecipe}', [RecipeController::class, 'view']);
            Route::get('/pdf/{dyeingRecipe}', [RecipeController::class, 'pdf']);

            Route::group(['prefix' => 'machine'], function () {
                Route::get('/{dyeingRecipe}', [BatchRecipeMachineController::class, 'index']);
                Route::post('/{dyeingRecipe}', [BatchRecipeMachineController::class, 'store']);
                Route::delete('/{dyeingRecipe}/{batchMachineAllocation}', [BatchRecipeMachineController::class, 'destroy']);
            });

            Route::group(['prefix' => '/details'], function () {
                Route::get('/{dyeingRecipe}', [RecipeDetailsController::class, 'getDetails']);
                Route::post('/{dyeingRecipe}', [RecipeDetailsController::class, 'store']);
                Route::post('/{dyeingRecipe}/batch-store', [RecipeDetailsController::class, 'batchStore']);
                Route::delete('/{dyeingRecipeDetail}', [RecipeDetailsController::class, 'destroy']);
            });

            Route::get('/requisition-entry', [SubDyeingRecipeRequisitionController::class, 'index']);
            Route::post('/requisition-entry/{dyeingRecipe}/store', [SubDyeingRecipeRequisitionController::class, 'store']);

            Route::get('/{any?}', [RecipeController::class, 'create'])->where('any', '.*');
        });

        Route::group(['prefix' => 'multiple-recipe-download'], function () {
            Route::get('/', [MultipleRecipeDownloadController::class, 'index']);
            Route::get('/search', [MultipleRecipeDownloadController::class, 'search']);
            Route::get('/download', [MultipleRecipeDownloadController::class, 'excelDownload']);
            Route::get('/pdf-download', [MultipleRecipeDownloadController::class, 'pdfDownload']);
        });
    });

    Route::group(['prefix' => 'dyeing-production'], function () {
        Route::get('/', [DyeingProductionController::class, 'index']);
        Route::post('/', [DyeingProductionController::class, 'store']);
        Route::put('{subDyeingProduction}', [DyeingProductionController::class, 'update']);
        Route::get('{subDyeingProduction}/edit', [DyeingProductionController::class, 'edit']);
        Route::get('get-batch-data/{subDyeingBatch}', [DyeingProductionController::class, 'getBatchData']);
        Route::delete('{subDyeingProduction}', [DyeingProductionController::class, 'destroy']);
        Route::get('view/{subDyeingProduction}', [DyeingProductionController::class, 'show']);
        Route::get('pdf/{subDyeingProduction}', [DyeingProductionController::class, 'pdf']);

        Route::get('/{any?}', [DyeingProductionController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => 'dryer'], function () {
        Route::get('/', [SubDryerController::class, 'index']);
        Route::post('/', [SubDryerController::class, 'store']);
        Route::put('/{subDryer}', [SubDryerController::class, 'update']);
        Route::get('/{subDryer}/edit', [SubDryerController::class, 'edit']);
        Route::delete('/{subDryer}', [SubDryerController::class, 'destroy']);

        Route::get('/get-batch-or-order-details', SubDryerSearchBatchOrOrderDetailsController::class);

        Route::get('/{any?}', [SubDryerController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => 'slitting'], function () {
        Route::get('/', [SlittingController::class, 'index']);
        Route::post('/', [SlittingController::class, 'store']);
        Route::put('/{subSlitting}', [SlittingController::class, 'update']);
        Route::get('/{subSlitting}/edit', [SlittingController::class, 'edit']);
        Route::delete('/delete/{subSlitting}', [SlittingController::class, 'destroy']);
        Route::get('/get-batch-or-order-details', SubSlittingSearchBatchOrOrderDetailsController::class);

        Route::get('/{any?}', [SlittingController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => 'stenter'], function () {
        Route::get('/', [SubDyeingStenterController::class, 'index']);
        Route::post('/', [SubDyeingStenterController::class, 'store']);
        Route::put('/{subDyeingStentering}', [SubDyeingStenterController::class, 'update']);
        Route::get('/{subDyeingStentering}/edit', [SubDyeingStenterController::class, 'edit']);
        Route::delete('/{subDyeingStentering}', [SubDyeingStenterController::class, 'destroy']);

        Route::get('/get-batch-or-order-details', SubDyeingStenterSearchBatchOrOrderDetailController::class);

        Route::get('/{any?}', [SubDyeingStenterController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => 'compactor'], function () {
        Route::get('/', [CompactorController::class, 'index']);
        Route::post('/', [CompactorController::class, 'store']);
        Route::put('/{subCompactor}', [CompactorController::class, 'update']);
        Route::get('/{subCompactor}/edit', [CompactorController::class, 'edit']);
        Route::delete('/delete/{subCompactor}', [CompactorController::class, 'destroy']);
        Route::get('/get-batch-or-order-details', CompactorSearchBatchOrOrderDetailsController::class);

        Route::get('/{any?}', [CompactorController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => 'tumbles'], function () {
        Route::get('/', [SubDyeingTumbleController::class, 'index']);
        Route::post('/', [SubDyeingTumbleController::class, 'store']);
        Route::get('/{dyeingTumble}/edit', [SubDyeingTumbleController::class, 'edit']);
        Route::put('/{dyeingTumble}', [SubDyeingTumbleController::class, 'update']);
        Route::delete('/{dyeingTumble}', [SubDyeingTumbleController::class, 'destroy']);

        Route::get('/get-batch-or-order-details', SearchForTumbleDetailsController::class);

        Route::get('/{any?}', [SubDyeingTumbleController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => 'peaches'], function () {
        Route::get('/', [SubDyeingPeachController::class, 'index']);
        Route::post('/', [SubDyeingPeachController::class, 'store']);
        Route::get('/{dyeingPeach}/edit', [SubDyeingPeachController::class, 'edit']);
        Route::put('/{dyeingPeach}', [SubDyeingPeachController::class, 'update']);
        Route::delete('/{dyeingPeach}', [SubDyeingPeachController::class, 'destroy']);

        Route::get('get-batch-or-order-details', SearchForPeachDetailsController::class);

        Route::get('/{any?}', [SubDyeingPeachController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => 'finishing-productions'], function () {
        Route::get('/', [SubDyeingFinishingProductionController::class, 'index']);
        Route::post('/', [SubDyeingFinishingProductionController::class, 'store']);
        Route::get('/{dyeingFinishingProduction}/edit', [SubDyeingFinishingProductionController::class, 'edit']);
        Route::put('/{dyeingFinishingProduction}', [SubDyeingFinishingProductionController::class, 'update']);
        Route::delete('/{dyeingFinishingProduction}', [SubDyeingFinishingProductionController::class, 'destroy']);

        Route::get('/get-batch-or-order-details', SearchBatchOrOrderDetailsController::class);

        Route::get('/{any?}', [SubDyeingFinishingProductionController::class, 'create'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'sub-dyeing-goods-delivery'], function () {
        Route::get('/', [SubDyeingGoodsDeliveryController::class, 'index']);
        Route::post('/', [SubDyeingGoodsDeliveryController::class, 'store']);
        Route::put('/{subDyeingGoodsDelivery}', [SubDyeingGoodsDeliveryController::class, 'update']);
        Route::get('/{subDyeingGoodsDelivery}/edit', [SubDyeingGoodsDeliveryController::class, 'edit']);

        Route::get('/gate-pass-view/{subDyeingGoodsDelivery}', [SubDyeingGoodsDeliveryController::class, 'gatePassView']);
        Route::get('/gate-pass-view-pdf/{subDyeingGoodsDelivery}', [SubDyeingGoodsDeliveryController::class, 'gatePassViewPdf']);

        Route::get('/challan-and-gate-pass-view/{subDyeingGoodsDelivery}', [SubDyeingGoodsDeliveryController::class, 'gateChallanPassView']);
        Route::get('/challan-and-gate-pass-view-pdf/{subDyeingGoodsDelivery}', [SubDyeingGoodsDeliveryController::class, 'gateChallanPassPdf']);

        Route::get('/bill-view/{subDyeingGoodsDelivery}', [SubDyeingGoodsDeliveryController::class, 'billView']);
        Route::get('/bill-view-pdf/{subDyeingGoodsDelivery}', [SubDyeingGoodsDeliveryController::class, 'billViewPdf']);

        Route::delete('/{subDyeingGoodsDelivery}', [SubDyeingGoodsDeliveryController::class, 'destroy']);

        Route::get('/get-batch-or-order-details', SubDyeingGoodsDeliverySearchController::class);

        Route::get('/{any?}', [SubDyeingGoodsDeliveryController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => 'inbound-billings'], function () {
        Route::get('/', [SubInboundBillingController::class, 'index']);
        Route::get('/{any?}', [SubInboundBillingController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => 'tube-compacting'], function () {
        Route::get('/', [SubDyeingTubeCompactingController::class, 'index']);
        Route::post('/', [SubDyeingTubeCompactingController::class, 'store']);
        Route::put('/{subDyeingTubeCompacting}', [SubDyeingTubeCompactingController::class, 'update']);
        Route::get('/{subDyeingTubeCompacting}/edit', [SubDyeingTubeCompactingController::class, 'edit']);
        Route::delete('/{subDyeingTubeCompacting}', [SubDyeingTubeCompactingController::class, 'destroy']);

        Route::get('/get-batch-or-order-details', SubDyeingTubeCompactingDetailSearchController::class);

        Route::get('/{any?}', [SubDyeingTubeCompactingController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => 'squeezer'], function () {
        Route::get('/', [SubDyeingSqueezerController::class, 'index']);
        Route::post('/', [SubDyeingSqueezerController::class, 'store']);
        Route::put('/{subDyeingSqueezer}', [SubDyeingSqueezerController::class, 'update']);
        Route::get('/{subDyeingSqueezer}/edit', [SubDyeingSqueezerController::class, 'edit']);
        Route::delete('/{subDyeingSqueezer}', [SubDyeingSqueezerController::class, 'destroy']);

        Route::get('/get-batch-or-order-details', SubDyeingSqueezerDetailSearchController::class);

        Route::get('/{any?}', [SubDyeingSqueezerController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => 'ht-set'], function () {
        Route::get('/', [SubDyeingHtSetController::class, 'index']);
        Route::post('/', [SubDyeingHtSetController::class, 'store']);
        Route::put('/{subDyeingHtSet}', [SubDyeingHtSetController::class, 'update']);
        Route::get('/{subDyeingHtSet}/edit', [SubDyeingHtSetController::class, 'edit']);
        Route::delete('/{subDyeingHtSet}', [SubDyeingHtSetController::class, 'destroy']);

        Route::get('/get-batch-or-order-details', SubDyeingHtSetDetailSearchController::class);

        Route::get('/{any?}', [SubDyeingHtSetController::class, 'create'])->where('any', '.*');
    });

    Route::group(['prefix' => 'api/v1'], function () {
        Route::get('/order-default-selects', OrderDefaultSelectController::class);
        Route::get('/order-details-filter-for-batch', OrderDetailsFilterController::class);
        Route::get('/batch-search-for-recipe', BatchSearchController::class);
        Route::get('/textile-operations/select-search', SubTextileOperationSearchController::class);
        Route::get('/textile-processes/select-search', SubTextileProcessSearchController::class);
        Route::get('/textile-parties/select-search', SubTextilePartiesSearchController::class);
        Route::get('/get-colors', ColorsController::class);
        Route::get('/get-fabric-construction-type', FabricConstructionTypeController::class);
        Route::get('/textile-grey-store/{factoryId}', SubTextileGreyStoreApiController::class);
        Route::get('/textile-order/{factoryId}', SubTextileOrderApiController::class);
        Route::get('/party-textile-order/{factoryId}/{partyId}', SubTextileOrderApiController::class);
        Route::get('/supplier-wise-textile-orders/{factoryId}/{supplierId}', SupplierWiseSubTextileOrdersController::class);
        Route::get('/get-sub-dyeing-units/{factoryId}', SubDyeingUnitsController::class);
        Route::get('/get-color-ranges', ColorRangesController::class);
        Route::get('/fetch-batches/{factoryId}/{supplierId?}', SubDyeingBatchApiController::class);
        Route::get('/get-dyeing-machines', DyeingMachinesController::class);
        Route::get('/get-dye-receipe-operations/{factoryId}', DyeingRecipeOperationsController::class);
        Route::get('/get-dye-operation-functions/{dyeReceipeOperationId}', DyeingOperationFunctionsController::class);
        Route::get('/get-dyes-items', DyesItemsController::class);
        Route::get('/get-shifts', ShiftsApiController::class);
        Route::get('/order-wise-dyeing-batch/{orderId}', SubTextileOrderWiseSubDyeingBatchApiController::class);
        Route::get('/factory-wise-variable-setting/{factoryId}', FactoryWiseVariableSettingController::class);
        Route::get('/get-fabric-composition', FabricCompositionApiController::class);
        Route::get('/get-count-and-fabric-type/{composition}', CountAndFabricTypeApiController::class);
        Route::get('/fetch-factory-location/{factory}', FactoryWiseLocationApiController::class);
    });

    Route::resource('sub-grey-store', SubContractGreyStoreController::class, [
        'names' => [
            'index' => 'sub-grey-store.index',
            'store' => 'sub-grey-store.store',
            'update' => 'sub-grey-store.update',
            'edit' => 'sub-grey-store.edit',
            'destroy' => 'sub-grey-store.destroy',
        ],
    ]);

    Route::resource('dyeing-floor', DyeingFloorController::class, [
        'names' => [
            'index' => 'dyeing-floor.index',
            'store' => 'dyeing-floor.store',
            'update' => 'dyeing-floor.update',
            'edit' => 'dyeing-floor.edit',
            'destroy' => 'dyeing-floor.destroy',
        ],
    ]);

    Route::resource('dyeing-machine', DyeingMachineController::class, [
        'names' => [
            'index' => 'dyeing-machine.index',
            'store' => 'dyeing-machine.store',
            'update' => 'dyeing-machine.update',
            'edit' => 'dyeing-machine.edit',
            'destroy' => 'dyeing-machine.destroy',
        ],
    ]);

    Route::resource('sub-dyeing-recipe-operation', SubDyeingRecipeOperationController::class, [
        'names' => [
            'index' => 'sub-dyeing-recipe-operation.index',
            'store' => 'sub-dyeing-recipe-operation.store',
            'update' => 'sub-dyeing-recipe-operation.update',
            'edit' => 'sub-dyeing-recipe-operation.edit',
            'destroy' => 'sub-dyeing-recipe-operation.destroy',
        ],
    ]);

    Route::resource('sub-dyeing-operation-function', SubDyeingOperationFunctionController::class, [
        'names' => [
            'index' => 'sub-dyeing-operation-function.index',
            'store' => 'sub-dyeing-operation-function.store',
            'update' => 'sub-dyeing-operation-function.update',
            'edit' => 'sub-dyeing-operation-function.edit',
            'destroy' => 'sub-dyeing-operation-function.destroy',
        ],
    ]);

    Route::resource('sub-dyeing-variable', SubDyeingVariableController::class, [
        'names' => [
            'index' => 'sub-dyeing-variable.index',
            'store' => 'sub-dyeing-variable.store',
        ],
    ]);

    Route::get('factory-wise-dye-re-operation-name', [SubDyeingOperationFunctionController::class, 'dyeingReOperation']);

    Route::group(['prefix' => 'report'], function () {
        Route::group(['prefix' => 'sub-grey-store/stock-summery'], function () {
            Route::get('', [SubGreyStoreStockSummeryReportController::class, 'view']);
            Route::get('date-wise', [SubGreyStoreStockSummeryReportController::class, 'getReport']);
            Route::get('date-wise/pdf', [SubGreyStoreStockSummeryReportController::class, 'pdf']);
            Route::get('date-wise/excel', [SubGreyStoreStockSummeryReportController::class, 'excel']);
        });

        Route::group(['prefix' => 'dyeing-production'], function () {
            Route::group(['prefix' => 'date-wise'], function () {
                Route::get('', [DyeingProductionDailyReportController::class, 'view']);
                Route::get('get', [DyeingProductionDailyReportController::class, 'getReport']);
                Route::get('pdf', [DyeingProductionDailyReportController::class, 'pdf']);
                Route::get('excel', [DyeingProductionDailyReportController::class, 'excel']);
            });

            Route::group(['prefix' => 'daily'], function () {
                Route::get('', [SubDyeingDailyProductionReportController::class, 'index']);
                Route::get('fetch-report', [SubDyeingDailyProductionReportController::class, 'getReport']);
                Route::get('pdf', [SubDyeingDailyProductionReportController::class, 'generatePdf']);
                Route::get('excel', [SubDyeingDailyProductionReportController::class, 'generateExcel']);
            });
        });

        Route::group(['prefix' => 'party-order'], function () {
            Route::get('', [PartyAndOrderWiseReportController::class, 'view']);
            Route::get('get-report', [PartyAndOrderWiseReportController::class, 'getReport']);
            Route::get('pdf', [PartyAndOrderWiseReportController::class, 'pdf']);
            Route::get('excel', [PartyAndOrderWiseReportController::class, 'excel']);
        });

        Route::group(['prefix' => 'batch'], function () {
            Route::get('', [BatchReportController::class, 'view']);
            Route::get('get-report', [BatchReportController::class, 'getReport']);
            Route::get('pdf', [BatchReportController::class, 'pdf']);
            Route::get('excel', [BatchReportController::class, 'excel']);

            Route::group(['prefix' => 'costing'], function () {
                Route::get('', [SubDyeingBatchCostingReportController::class, 'index']);
                Route::get('report', [SubDyeingBatchCostingReportController::class, 'generateReport']);
                Route::get('pdf', [SubDyeingBatchCostingReportController::class, 'generatePdf']);
                Route::get('excel', [SubDyeingBatchCostingReportController::class, 'generateExcel']);
            });
        });

        Route::group(['prefix' => 'order'], function () {
            Route::get('', [OrderReportController::class, 'view']);
            Route::get('get-report', [OrderReportController::class, 'getReport']);
            Route::get('pdf', [OrderReportController::class, 'pdf']);
            Route::get('excel', [OrderReportController::class, 'excel']);

            Route::group(['prefix' => 'profit-loss'], function () {
                Route::get('', [OrderProfitLossController::class, 'view']);
                Route::get('get-report', [OrderProfitLossController::class, 'getReport']);
                Route::get('pdf', [OrderProfitLossController::class, 'pdf']);
                Route::get('excel', [OrderProfitLossController::class, 'excel']);
            });
        });

        Route::group(['prefix' => 'finishing-production'], function () {
            Route::group(['prefix' => 'daily'], function () {
                Route::get('', [SubDyeingFinishingProductionDailyReportController::class, 'index']);
                Route::get('report', [SubDyeingFinishingProductionDailyReportController::class, 'generate']);
                Route::get('pdf', [SubDyeingFinishingProductionDailyReportController::class, 'generatePdf']);
                Route::get('excel', [SubDyeingFinishingProductionDailyReportController::class, 'generateExcel']);
            });
        });

        Route::group(['prefix' => 'dyes-chemical'], function () {
            Route::group(['prefix' => 'costing'], function () {
                Route::get('', [SubDyeingDyesChemicalStoreStatementController::class, 'index']);
                Route::get('report', [SubDyeingDyesChemicalStoreStatementController::class, 'generateReport']);
                Route::get('pdf', [SubDyeingDyesChemicalStoreStatementController::class, 'generatePdf']);
                Route::get('excel', [SubDyeingDyesChemicalStoreStatementController::class, 'generateExcel']);
            });
        });

        Route::group(['prefix' => 'dyeing-ledger-report'], function () {
            Route::get('', [DyeingLedgerReportController::class, 'index']);
            Route::get('/get-report', [DyeingLedgerReportController::class, 'getReport']);
            Route::get('/pdf', [DyeingLedgerReportController::class, 'pdf']);
            Route::get('/excel', [DyeingLedgerReportController::class, 'excel']);
        });

        Route::group(['prefix' => 'order-wise-stock-report'], function () {
            Route::get('', [OrderWiseStockReportController::class, 'index']);
            Route::get('/get-report', [OrderWiseStockReportController::class, 'getReport']);
            Route::get('/pdf', [OrderWiseStockReportController::class, 'pdf']);
            Route::get('/excel', [OrderWiseStockReportController::class, 'excel']);
        });

        Route::group(['prefix' => 'date-wise-delivery-report'], function () {
            Route::get('', [DateWiseDeliveryReportController::class, 'index']);
            Route::get('/get-report', [DateWiseDeliveryReportController::class, 'getReport']);
            Route::get('/pdf', [DateWiseDeliveryReportController::class, 'pdf']);
            Route::get('/excel', [DateWiseDeliveryReportController::class, 'excel']);
        });

        Route::get('factories-batch', [BatchReportController::class, 'factoryBatch']);
        Route::get('factories-supplier', [PartyAndOrderWiseReportController::class, 'getSupplier']);
        Route::get('suppliers-order', [PartyAndOrderWiseReportController::class, 'getOrder']);
        Route::get('factories-order', [OrderReportController::class, 'factoryOrder']);
        Route::get('suppliers-order', [OrderProfitLossController::class, 'getOrder']);

        Route::group(['prefix' => 'grey-fabric-stock-summary'], function () {
            Route::get('', [GreyFabricStockSummaryReportController::class, 'index']);
            Route::get('get-report-data', [GreyFabricStockSummaryReportController::class, 'getReportData']);
            Route::get('pdf', [GreyFabricStockSummaryReportController::class, 'pdf']);
            Route::get('excel', [GreyFabricStockSummaryReportController::class, 'excel']);
        });
    });
});
