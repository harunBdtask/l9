<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Dryer\DryerController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Peach\PeachController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Recipe\RecipeController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Tumble\TumbleController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\API\DefaultValueController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Slitting\SlittingController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Recipe\BatchSearchController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\API\PaymentBasisApiController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Compactor\CompactorController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Recipe\RecipeDetailController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\API\DyeingBatchNosApiController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Stentering\StenteringController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\API\TextileOrdersNoApiController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingBatch\DyeingBatchController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Recipe\RecipeRequisitionController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\TextileOrder\TextileOrderController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\API\FabricSalesOrdersNoApiController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Tumble\SearchTumbleDetailsController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Peach\SearchForPeachDetailsController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\API\BuyerWiseSubTextileOrderController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\API\FactoryBuyerWiseBatchNosController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingBatch\DyeingBatchDetailController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Slitting\SearchSlittingDetailsController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\API\SearchTextileOrderDetailsApiController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Compactor\SearchCompactorDetailsController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingProduction\DyeingProductionController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Stentering\SearchStenteringDetailsController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Recipe\BatchWiseRecipeDetailsSearchController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Dryer\DryerSearchBatchOrOrderDetailsController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingGoodsDelivery\DyeingGoodsDeliveryController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingBatch\DyeingBatchMachineAllocationController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingProduction\SearchBatchDataForProductionDetails;
use SkylarkSoft\GoRMG\Dyeing\Controllers\TextileOrder\SearchFabricSalesOrderDetailsController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingFinishingProduction\FinishingProductionController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingFinishingProduction\SearchBatchOrOrderDetailsController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingGoodsDelivery\SearchDyeingGoodsDeliveryOrderDetailsController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Reports\PartyAndOrderWiseController\PartyAndOrderWiseReportController;
use SkylarkSoft\GoRMG\Dyeing\Controllers\Reports\DyeingProductionDailyReport\DyeingProductionDailyReportController;

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'dyeing'], function () {

    Route::group(['prefix' => '/textile-orders'], function () {
        Route::get('/', [TextileOrderController::class, 'index']);
        Route::post('/', [TextileOrderController::class, 'store']);
        Route::get('/{textileOrder}/edit', [TextileOrderController::class, 'edit']);
        Route::put('/{textileOrder}', [TextileOrderController::class, 'update']);
        Route::delete('/{textileOrder}', [TextileOrderController::class, 'destroy']);
        Route::get('view/{textileOrder}', [TextileOrderController::class, 'view']);
        Route::get('pdf/{textileOrder}',[TextileOrderController::class,'pdf']);

        Route::get('/get-fabric-sales-order-details/{fabricSaleOrderId}',
            SearchFabricSalesOrderDetailsController::class);

        Route::get('/{any?}', [TextileOrderController::class, 'create'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'dyeing-batches'], function () {
        Route::get('/', [DyeingBatchController::class, 'index']);
        Route::post('/', [DyeingBatchController::class, 'store']);
        Route::get('/{dyeingBatch}/edit', [DyeingBatchController::class, 'edit']);
        Route::put('/{dyeingBatch}', [DyeingBatchController::class, 'update']);
        Route::get('/view/{dyeingBatch}', [DyeingBatchController::class, 'view']);
        Route::delete('/{dyeingBatch}', [DyeingBatchController::class, 'destroy']);

        Route::group(['prefix' => 'details'], function () {
            Route::get('/{dyeingBatch}', [DyeingBatchDetailController::class, 'getDetails']);
            Route::post('/{dyeingBatch}', [DyeingBatchDetailController::class, 'store']);
            Route::put('/{dyeingBatch}', [DyeingBatchDetailController::class, 'update']);
            Route::delete('/{dyeingBatch}/{dyeingBatchDetail}', [DyeingBatchDetailController::class, 'destroy']);
        });

        Route::group(['prefix' => 'machine-allocations'], function () {
            Route::get('/{dyeingBatch}', [DyeingBatchMachineAllocationController::class, 'index']);
            Route::post('/{dyeingBatch}', [DyeingBatchMachineAllocationController::class, 'store']);
            Route::delete('/{dyeingBatch}/{dyeingBatchMachineAllocation}',
                [DyeingBatchMachineAllocationController::class, 'destroy']);
        });

        Route::get('/{any?}', [DyeingBatchController::class, 'create'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'recipes'], function () {
        Route::get('', [RecipeController::class, 'index']);
        Route::post('', [RecipeController::class, 'store']);
        Route::get('/{dyeingRecipe}/edit', [RecipeController::class, 'edit']);
        Route::put('/{dyeingRecipe}', [RecipeController::class, 'update']);
        Route::delete('/{dyeingRecipe}', [RecipeController::class, 'destroy']);

        Route::get('get-batch/{dyeingBatchId}', BatchSearchController::class);
        Route::get('/view/{dyeingRecipe}', [RecipeController::class, 'view']);
        Route::get('/pdf/{dyeingRecipe}',[RecipeController::class, 'pdf']);

        Route::group(['prefix' => 'details'], function () {
            Route::get('/{dyeingRecipe}', [RecipeDetailController::class, 'getDetails']);
            Route::post('/{dyeingRecipe}', [RecipeDetailController::class, 'store']);
            Route::delete('/{dyeingRecipeDetail}', [RecipeDetailController::class, 'destroy']);
            Route::post('/{dyeingRecipe}/batch-store', [RecipeDetailController::class, 'storeDetails']);
        });

        Route::group(['prefix' => 'requisitions'], function () {
            Route::get('', [RecipeRequisitionController::class, 'index']);
            Route::post('/{dyeingRecipe}', [RecipeRequisitionController::class, 'store']);
        });

        Route::get('/copy-form-previous-recipe/{batchId}', BatchWiseRecipeDetailsSearchController::class);

        Route::get('/{any?}', [RecipeController::class, 'create'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'productions'], function () {
        Route::get('/', [DyeingProductionController::class, 'index']);
        Route::post('/', [DyeingProductionController::class, 'store']);
        Route::get('/{dyeingProduction}/edit', [DyeingProductionController::class, 'edit']);
        Route::put('/{dyeingProduction}', [DyeingProductionController::class, 'update']);
        Route::delete('/{dyeingProduction}', [DyeingProductionController::class, 'destroy']);

        Route::get('get-batch-data/{dyeingBatch}', SearchBatchDataForProductionDetails::class);
        Route::get('/view/{dyeingProduction}', [DyeingProductionController::class, 'view']);
        Route::get('/pdf/{dyeingProduction}',[DyeingProductionController::class,'pdf']);

        Route::get('/{any?}', [DyeingProductionController::class, 'create'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'finishing-productions'], function () {
        Route::get('/', [FinishingProductionController::class, 'index']);
        Route::post('/', [FinishingProductionController::class, 'store']);

        Route::get('/get-batch-or-order-data', SearchBatchOrOrderDetailsController::class);
        Route::get('/{dyeingFinishingProduction}/edit', [FinishingProductionController::class, 'edit']);
        Route::put('/{dyeingFinishingProduction}', [FinishingProductionController::class, 'update']);
        Route::delete('/{dyeingFinishingProduction}', [FinishingProductionController::class, 'destroy']);
        Route::get('/view/{dyeingFinishingProduction}', [FinishingProductionController::class, 'view']);
        Route::get('/pdf/{dyeingFinishingProduction}',[FinishingProductionController::class,'pdf']);

        Route::get('/{any?}', [FinishingProductionController::class, 'create'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'dryer'], function () {
        Route::get('/', [DryerController::class, 'index']);
        Route::post('/', [DryerController::class, 'store']);
        Route::get('/get-batch-or-order-data', DryerSearchBatchOrOrderDetailsController::class);
        Route::get('/{dryer}/edit', [DryerController::class, 'edit']);
        Route::put('/{dryer}', [DryerController::class, 'update']);
        Route::delete('/{dryer}', [DryerController::class, 'destroy']);

        Route::get('/{any?}', [DryerController::class, 'create'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'slittings'], function () {
        Route::get('/', [SlittingController::class, 'index']);
        Route::post('/', [SlittingController::class, 'store']);
        Route::get('/{slitting}/edit', [SlittingController::class, 'edit']);
        Route::put('/{slitting}', [SlittingController::class, 'update']);
        Route::delete('/{slitting}', [SlittingController::class, 'destroy']);

        Route::get('/get-batch-or-order-details', SearchSlittingDetailsController::class);

        Route::get('/{any?}', [SlittingController::class, 'create'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'stenterings'], function () {
        Route::get('/', [StenteringController::class, 'index']);
        Route::post('/', [StenteringController::class, 'store']);
        Route::get('/get-batch-or-order-data', SearchStenteringDetailsController::class);
        Route::get('/{stentering}/edit', [StenteringController::class, 'edit']);
        Route::put('/{stentering}', [StenteringController::class, 'update']);
        Route::delete('/{stentering}', [StenteringController::class, 'destroy']);

        Route::get('/{any?}', [StenteringController::class, 'create'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'compactors'], function () {
        Route::get('/', [CompactorController::class, 'index']);
        Route::post('/', [CompactorController::class, 'store']);
        Route::get('/{compactor}/edit', [CompactorController::class, 'edit']);
        Route::put('/{compactor}', [CompactorController::class, 'update']);
        Route::delete('/{compactor}', [CompactorController::class, 'destroy']);

        Route::get('/get-compactor-details', SearchCompactorDetailsController::class);

        Route::get('/{any?}', [CompactorController::class, 'create'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'peaches'], function () {
        Route::get('/', [PeachController::class, 'index']);
        Route::post('/', [PeachController::class, 'store']);
        Route::get('/{peach}/edit', [PeachController::class, 'edit']);
        Route::put('/{peach}', [PeachController::class, 'update']);
        Route::delete('/{peach}', [PeachController::class, 'destroy']);

        Route::get('/get-peach-details', SearchForPeachDetailsController::class);

        Route::get('/{any?}', [PeachController::class, 'create'])
            ->where('any', '.*');
    });
    
    Route::group(['prefix' => 'tumbles'], function () {
        Route::get('/', [TumbleController::class, 'index']);
        Route::post('/', [TumbleController::class, 'store']);
        Route::get('/{tumble}/edit', [TumbleController::class, 'edit']);
        Route::put('/{tumble}', [TumbleController::class, 'update']);
        Route::delete('/{tumble}', [TumbleController::class, 'destroy']);

        Route::get('/get-batch-or-order-data', SearchTumbleDetailsController::class);

        Route::get('/{any?}', [TumbleController::class, 'create'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'dyeing-goods-delivery'], function () {
        Route::get('/', [DyeingGoodsDeliveryController::class, 'index']);
        Route::post('/', [DyeingGoodsDeliveryController::class, 'store']);
        Route::get('/{dyeingGoodsDelivery}/edit', [DyeingGoodsDeliveryController::class, 'edit']);
        Route::put('/{dyeingGoodsDelivery}', [DyeingGoodsDeliveryController::class, 'update']);
        Route::delete('/{dyeingGoodsDelivery}', [DyeingGoodsDeliveryController::class, 'destroy']);

        Route::get('/get-batch-or-order-data', SearchDyeingGoodsDeliveryOrderDetailsController::class);

        Route::get('/{any?}', [DyeingGoodsDeliveryController::class, 'create'])
            ->where('any', '.*');
    });

    Route::get('daily-dyeing-production-report', [DyeingProductionDailyReportController::class, 'view']);
    Route::get('date-wise-dyeing-production-daily-report', [DyeingProductionDailyReportController::class, 'getReport']);
    Route::get('dyeing-production-daily-report-pdf', [DyeingProductionDailyReportController::class, 'pdf']);
    Route::get('dyeing-production-daily-report-excel', [DyeingProductionDailyReportController::class, 'excel']);

    Route::get('party-and-order-wise-report', [PartyAndOrderWiseReportController::class, 'view']);
    Route::get('factory-wise-buyer', [PartyAndOrderWiseReportController::class, 'getBuyer']);
    Route::get('factory-wise-order', [PartyAndOrderWiseReportController::class, 'getOrder']);
    Route::get('party-and-order-wise-report/get-report', [PartyAndOrderWiseReportController::class, 'getReport']);
    Route::get('party-and-order-wise-report-pdf', [PartyAndOrderWiseReportController::class, 'pdf']);
    Route::get('party-and-order-wise-report-excel', [PartyAndOrderWiseReportController::class, 'excel']);

    Route::group(['prefix' => 'api/v1/'], function () {
        Route::get('/get-payment-basis', PaymentBasisApiController::class);
        Route::get('/get-fabric-sales-orders-no/{buyerId}', FabricSalesOrdersNoApiController::class);
        Route::get('/get-textile-orders-no/{textileOrderId}', TextileOrdersNoApiController::class);
        Route::get('/get-textile-orders-details/{textileOrderId}', SearchTextileOrderDetailsApiController::class);
        Route::get('/get-batch-nos/{buyerId}', DyeingBatchNosApiController::class);
        Route::get('/get-batches/{factoryId}/{buyerId}', FactoryBuyerWiseBatchNosController::class);
        Route::get('/buyer-wise-textile-orders/{factoryId}/{buyerId}', BuyerWiseSubTextileOrderController::class);
        Route::get('/get-default-value',DefaultValueController::class);
    });

});
