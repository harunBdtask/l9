<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Iedroplets\Controllers\ContainerPlannings\ContainerProfileController;
use SkylarkSoft\GoRMG\Planing\Controllers\API\CapacityAvailabilityAPIController;
use SkylarkSoft\GoRMG\Planing\Controllers\API\CapacityDefaultsAPIController;
use SkylarkSoft\GoRMG\Planing\Controllers\API\CapacityPlanEntryAPIController;
use SkylarkSoft\GoRMG\Planing\Controllers\API\ContainerProfileApiController;
use SkylarkSoft\GoRMG\Planing\Controllers\API\PurchaseOrderApiController;
use SkylarkSoft\GoRMG\Planing\Controllers\CapacityPlaning\ContainerFillUpController;
use SkylarkSoft\GoRMG\Planing\Controllers\CapacityPlaning\ContainerSummariesController;
use SkylarkSoft\GoRMG\Planing\Controllers\Reports\CapacityMarketingComparisonController;
use SkylarkSoft\GoRMG\Planing\Controllers\Settings\BuyerCapacityController;
use SkylarkSoft\GoRMG\Planing\Controllers\Settings\ItemCategoryController;

Route::group(['middleware' => ['web', 'auth', 'menu-auth']], function () {
    Route::group(['prefix' => 'api/v1'], function () {
        Route::get('fetch-container-profiles', [ContainerProfileApiController::class, '__invoke']);
        Route::get('fetch-purchase-orders', [PurchaseOrderApiController::class, '__invoke']);
        Route::get('fill-up-containers', [ContainerFillUpController::class, '__invoke']);
    });

    Route::group(['prefix' => 'planning', 'middleware' => 'web'], function () {
        Route::group(['prefix' => '/container-profiles'], function () {
            Route::get('', [ContainerProfileController::class, 'index']);
            Route::post('', [ContainerProfileController::class, 'store']);
            Route::get('/{containerProfile}/edit', [ContainerProfileController::class, 'edit']);
            Route::put('/{containerProfile}', [ContainerProfileController::class, 'update']);
            Route::delete('/{containerProfile}', [ContainerProfileController::class, 'destroy']);
            Route::get('/search', [ContainerProfileController::class, 'search']);
            Route::get('/{any?}', [ContainerProfileController::class, 'create'])
                ->where('any', '.*');
        });

        Route::group(['prefix' => 'container-summaries'], function () {
            Route::get('', [ContainerSummariesController::class, 'index']);
            Route::post('', [ContainerSummariesController::class, 'store']);
            Route::get('/{containerSummaries}/edit', [ContainerSummariesController::class, 'edit']);
            Route::get('create', [ContainerSummariesController::class, 'create']);
        });

        Route::view('/capacity-planning-entry', 'planing::capacity-planning.capacity-planning-entry');
        Route::view('/capacity-availability', 'planing::capacity-planning.capacity-availability');

        Route::group(['prefix' => '/container-availability'], function () {
            Route::view('/', 'planing::container-availability.container-availability');
        });

        Route::group(['prefix' => 'reports'],function () {
           Route::get('capacity-marketing-comparisons',[CapacityMarketingComparisonController::class,'index']);
           Route::get('capacity-marketing-comparisons/pdf',[CapacityMarketingComparisonController::class,'pdf'])
           ->name('planning.reports.capacity-marketing-comparisons.pdf');
           Route::get('capacity-marketing-comparisons/excel',[CapacityMarketingComparisonController::class,'excel'])
           ->name('planning.reports.capacity-marketing-comparisons.excel');
            Route::get('capacity/pdf', [CapacityAvailabilityAPIController::class, 'pdf']);
        });

        Route::group(['prefix' => 'settings'], function () {
            Route::prefix('item-categories')->group(function () {
                Route::get('/', [ItemCategoryController::class, 'index'])
                    ->name('planning.settings.item-categories.index');

                Route::get('/create', [ItemCategoryController::class, 'create'])
                    ->name('planning.settings.item-categories.create');

                Route::post('/', [ItemCategoryController::class, 'store'])
                    ->name('planning.settings.item-categories.store');


                Route::get('/{id}', [ItemCategoryController::class, 'edit'])
                    ->name('planning.settings.item-categories.edit');

                Route::put('/{id}', [ItemCategoryController::class, 'update'])
                    ->name('planning.settings.item-categories.update');

                Route::delete('/{id}', [ItemCategoryController::class, 'destroy'])
                    ->name('planning.settings.item-categories.delete');
            });

            Route::prefix('buyers-capacity')->group(function () {
                Route::get('/', [BuyerCapacityController::class, 'index'])
                    ->name('planning.settings.buyer-capacity.index');

                Route::get('/create', [BuyerCapacityController::class, 'create'])
                    ->name('planning.settings.buyer-capacity.create');

                Route::post('/', [BuyerCapacityController::class, 'store'])
                    ->name('planning.settings.buyer-capacity.store');


                Route::get('/{id}', [BuyerCapacityController::class, 'edit'])
                    ->name('planning.settings.buyer-capacity.edit');

                Route::put('/{id}', [BuyerCapacityController::class, 'update'])
                    ->name('planning.settings.buyer-capacity.update');

                Route::delete('/{id}', [BuyerCapacityController::class, 'destroy'])
                    ->name('planning.settings.buyer-capacity.delete');
            });

        });
    });


    Route::group(['prefix' => 'api/v1/planning', 'middleware' => ['web', 'auth', 'menu-auth']], function () {
        Route::get('defaults', CapacityDefaultsAPIController::class);
        Route::get('/fetch-item-categories', [CapacityPlanEntryAPIController::class, 'fetchItemCategories']);
        Route::get('/factory-wise-buyers/{factoryId}', [CapacityPlanEntryAPIController::class, 'factoryWiseBuyers']);
        Route::group(['prefix' => 'capacity-plan'], function () {
            Route::post('search/line-wise', [CapacityPlanEntryAPIController::class, 'searchCapacityPlan']);
            Route::post('', [CapacityPlanEntryAPIController::class, 'save']);
            Route::delete('/{factoryCapacity}', [CapacityPlanEntryAPIController::class, 'delete']);
        });

        Route::group(['prefix' => 'capacity-availability'], function () {
            Route::post('search/capacity', [CapacityAvailabilityAPIController::class, 'searchCapacity']);
        });
    });
});
