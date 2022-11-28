<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Knitting\Controllers\API\OperatorApiController;
use SkylarkSoft\GoRMG\Knitting\Controllers\API\ShiftApiController;
use SkylarkSoft\GoRMG\Knitting\Controllers\CommonAPIController;
use SkylarkSoft\GoRMG\Knitting\Controllers\FabricBookingController;
use SkylarkSoft\GoRMG\Knitting\Controllers\FabricSalesOrder\FabricSalesOrderController;
use SkylarkSoft\GoRMG\Knitting\Controllers\FabricSalesOrder\FabricSalesOrderDetailController;
use SkylarkSoft\GoRMG\Knitting\Controllers\KnitCardController;
use SkylarkSoft\GoRMG\Knitting\Controllers\KnittingProduction\API\CollarCuffSearchApiController;
use SkylarkSoft\GoRMG\Knitting\Controllers\KnittingProduction\API\KnitCardSearchApiController;
use SkylarkSoft\GoRMG\Knitting\Controllers\KnittingProduction\API\KnittingProgramSearchApiController;
use SkylarkSoft\GoRMG\Knitting\Controllers\KnittingProduction\KnittingProductionController;
use SkylarkSoft\GoRMG\Knitting\Controllers\KnittingProductionPlanningController;
use SkylarkSoft\GoRMG\Knitting\Controllers\KnittingQC\API\GradePointApiController;
use SkylarkSoft\GoRMG\Knitting\Controllers\KnittingQC\KnittingQcController;
use SkylarkSoft\GoRMG\Knitting\Controllers\KnittingRollController;
use SkylarkSoft\GoRMG\Knitting\Controllers\PlanningInfo\PlanningInfoDetailController;
use SkylarkSoft\GoRMG\Knitting\Controllers\PlanningInfo\SearchEntityController;
use SkylarkSoft\GoRMG\Knitting\Controllers\PlanningInfo\PlanningInfoController;
use SkylarkSoft\GoRMG\Knitting\Controllers\Program\API\KnittingFloorApiController;
use SkylarkSoft\GoRMG\Knitting\Controllers\Program\API\MachineSearchApiController;
use SkylarkSoft\GoRMG\Knitting\Controllers\Program\API\ProgramApiController;
use SkylarkSoft\GoRMG\Knitting\Controllers\Program\ProgramCollarCuffDetailsController;
use SkylarkSoft\GoRMG\Knitting\Controllers\Program\ProgramController;
use SkylarkSoft\GoRMG\Knitting\Controllers\Program\ProgramFabricColorController;
use SkylarkSoft\GoRMG\Knitting\Controllers\Program\ProgramMachineController;
use SkylarkSoft\GoRMG\Knitting\Controllers\Program\ProgramStripeDetailsController;
use SkylarkSoft\GoRMG\Knitting\Controllers\Program\YarnRequisitionController;
use SkylarkSoft\GoRMG\Knitting\Controllers\RollWiseFabricDeliveryController;
use SkylarkSoft\GoRMG\Knitting\Controllers\YarnAllocationController;
use SkylarkSoft\GoRMG\Knitting\Controllers\Reports\DailyProductionReportController;
use SkylarkSoft\GoRMG\Knitting\Controllers\Reports\BuyerStyleReportController;
use SkylarkSoft\GoRMG\Knitting\Controllers\Reports\DailyKnittingReportController;
use SkylarkSoft\GoRMG\Knitting\Controllers\Reports\OrderStatusReportController;
use SkylarkSoft\GoRMG\Knitting\Controllers\Reports\YarnAllocationReportController;

Route::group([
    'prefix' => 'knitting',
    'middleware' => ['web', 'auth', 'menu-auth'],
    'namespace' => 'SkylarkSoft\GoRMG\Knitting\Controllers'
], function () {
    // fabric sales order
    Route::group(['prefix' => 'fabric-sales-order'], function () {
        Route::get('/', [FabricSalesOrderController::class, 'index']);
        Route::post('/', [FabricSalesOrderController::class, 'store']);
        Route::get('create', [FabricSalesOrderController::class, 'create']);
        Route::get('{id}/view', [FabricSalesOrderController::class, 'view']);
        Route::get('/{id}/edit', [FabricSalesOrderController::class, 'create']);
        Route::delete('{id}/delete', [FabricSalesOrderController::class, 'delete']);
        Route::get('{id}/view-v2',[FabricSalesOrderController::class, 'getView']);
    });

    Route::get('/fabric-booking-list', [FabricBookingController::class, '__invoke']);

    Route::get('/yarn-requisition-list', [YarnRequisitionController::class, 'list']);
    Route::get('/yarn-requisition/{id}/view', [YarnRequisitionController::class, 'reportView']);
    Route::get('/yarn-requisition/{id}/pdf', [YarnRequisitionController::class, 'reportPdf']);
    Route::delete('/yarn-requisition/{requisition}/delete', [YarnRequisitionController::class, 'delete']);

    Route::get('/yarn-requisition/excel-list-all', [YarnRequisitionController::class, 'listExcelAll']);
    Route::get('/yarn-requisition/excel-list-by-page', [YarnRequisitionController::class, 'listExcelByPage']);

    Route::view('/yarn-requisition/{any?}', 'knitting::planning');
    Route::view('/yarn-requisition-info', 'knitting::planning');

    Route::get('/planning-info-entry', [ProgramController::class, 'index']);
    Route::get('/get-fabric-colors', [ProgramController::class, 'getFabricColor']);
    Route::get('/program-list', [ProgramController::class, 'list']);
    Route::get('/program/{knittingProgram}/delete', [ProgramController::class, 'delete']);
    Route::get('/program/{id}/view', [ProgramController::class, 'view']);
    Route::get('/program/{id}/pdf', [ProgramController::class, 'pdf']);
    Route::get('/program/{id}/program-view', [ProgramController::class, 'programView']);
    Route::get('/program/{id}/program-view-pdf', [ProgramController::class, 'programViewPdf']);

    Route::post('/program/store', [ProgramController::class, 'store']);

    Route::group(['prefix' => 'roll-wise-fabric-delivery'], function () {
        Route::get('/', [RollWiseFabricDeliveryController::class, 'index']);
        Route::get('/search-deliverable-rolls', [RollWiseFabricDeliveryController::class, 'searchDeliverableRolls']);
        Route::get('/create', [RollWiseFabricDeliveryController::class, 'create']);
        Route::get('/{challan_no}/edit', [RollWiseFabricDeliveryController::class, 'edit']);
        Route::post('/{challan_no}/save', [RollWiseFabricDeliveryController::class, 'save']);
        Route::delete('/{id}/delete', [RollWiseFabricDeliveryController::class, 'delete']);
        Route::post('/detail/store', [RollWiseFabricDeliveryController::class, 'detailStore']);
        Route::delete('/detail/{id}/delete', [RollWiseFabricDeliveryController::class, 'detailDelete']);
    });

    Route::get('/yarn-allocation', [YarnAllocationController::class, 'index']);
    Route::get('/yarn-allocation/create', [YarnAllocationController::class, 'create']);
    Route::get('/yarn-allocation/{id}', [YarnAllocationController::class, 'show']);
    Route::get('/yarn-allocation/{id}/edit', [YarnAllocationController::class, 'create']);
    Route::get('/yarn-allocation/{id}/pdf', [YarnAllocationController::class, 'pdf']);
    Route::delete('/yarn-allocation/{allocation}/delete', [YarnAllocationController::class, 'delete']);

    Route::get('/knitting-production', [KnittingProductionController::class, 'index']);
    Route::group(['prefix' => 'knitting-qc'], function () {
        Route::get('/', [KnittingQcController::class, 'index']);
        Route::get('/search', [KnittingQcController::class, 'knitQcSearch']);
        Route::get('/qcable-rolls', [KnittingQcController::class, 'knitQcRollView']);
        Route::post('/{knitProgramRoll}/save', [KnittingQcController::class, 'save']);
        Route::get('/check/{any?}', [KnittingQcController::class, 'generateForm'])->where('any', '.*');
    });

    Route::group(['prefix' => 'knitting-roll'], function () {
        Route::get('', [KnittingRollController::class, 'index']);
        Route::get('/{knitProgramRoll}/view', [KnittingRollController::class, 'view']);
    });

    Route::group(['prefix' => 'knit-card'], function () {
        Route::get('/', [KnitCardController::class, 'index']);
        Route::get('/{id}/view', [KnitCardController::class, 'view']);
        Route::get('/{id}/view-2', [KnitCardController::class, 'view2']);
        Route::get('/{any?}', [KnitCardController::class, 'create'])->where('any', '.*');
        Route::post('/', [KnitCardController::class, 'store']);
        Route::delete('/{knitCard}/delete', [KnitCardController::class, 'delete']);
    });

    // reports
    Route::group(['prefix' => 'daily-production-report'], function () {
        Route::get('/', [DailyProductionReportController::class, 'index']);
        Route::get('/pdf', [DailyProductionReportController::class, 'pdf']);
        Route::get('/excel', [DailyProductionReportController::class, 'excel']);
    });
    Route::group(['prefix' => 'order-status-report'], function () {
        Route::get('/', [OrderStatusReportController::class, 'index']);
        Route::get('/pdf', [OrderStatusReportController::class, 'pdf']);
        Route::get('/excel', [OrderStatusReportController::class, 'excel']);
    });
    Route::group(['prefix' => 'daily-knitting-report'], function () {
        Route::get('/', [DailyKnittingReportController::class, 'index']);
        Route::get('/pdf', [DailyKnittingReportController::class, 'pdf']);
        Route::get('/excel', [DailyKnittingReportController::class, 'excel']);
    });
    Route::group(['prefix' => 'yarn-allocation-report'], function () {
        Route::get('/', [YarnAllocationReportController::class, 'index']);
        Route::get('/pdf', [YarnAllocationReportController::class, 'pdf']);
        Route::get('/excel', [YarnAllocationReportController::class, 'excel']);
    });

    // redirect all vue route -- program
    Route::get('program/{any?}', [ProgramController::class, 'create'])->where('any', '.*');
    Route::get('knitting-production/{any?}', [KnittingProductionController::class, 'index'])->where('any', '.*');
    Route::group(['prefix' => '/api/v1'], function () {
        Route::get('/get-operators', [OperatorApiController::class, '__invoke']);
        Route::get('/get-shifts', [ShiftApiController::class, '__invoke']);
        Route::get('/get-color-range', [FabricSalesOrderController::class, 'getColorRange']);
        Route::get('/get-fabric-description', [FabricSalesOrderController::class, 'getFabricDescription']);
        Route::get('/get-program-no', [KnitCardController::class, 'getProgramNo']);
        Route::get('/get-program-qty-info/{programId}/{colorId}', [CommonAPIController::class, 'getProgramQtyInfo']);
        Route::get('/get-knit-card-no', [KnitCardController::class, 'getKnitCardNo']);
        Route::get('/get-program-data/{id}', [KnitCardController::class, 'getProgramData']);
        Route::get('fabric-sales-order/{id}/edit', [FabricSalesOrderController::class, 'edit']);
        Route::get('/fabric-booking', [FabricSalesOrderController::class, 'getFabricBooking']);
        Route::get('/fabric-booking/{id}/breakdown', [FabricSalesOrderController::class, 'getFabricBookingBreakdown']);
        Route::post('fabric-sales-order', [FabricSalesOrderController::class, 'store']);
        Route::put('/fabric-sales-order/{salesOrder}', [FabricSalesOrderController::class, 'update']);
        Route::get('/planning_info_detail/{planning_info_id}', [PlanningInfoDetailController::class, 'edit']);
        Route::post('/planning_info_detail/{id?}', [PlanningInfoDetailController::class, 'store']);
        Route::get('/fabric-sales-order/{salesOrder}', [FabricSalesOrderController::class, 'view']);
        Route::delete('/fabric-sales-order/{salesOrder}', [FabricSalesOrderController::class, 'delete']);

        Route::group(['prefix' => '/plan-info'], function () {
            Route::get('/buyer-search', [SearchEntityController::class, 'buyerSearch']);
            Route::get('/style-search', [SearchEntityController::class, 'styleSearch']);
            Route::get('/unique-id-search', [SearchEntityController::class, 'uniqueIdSearch']);
            Route::get('/po-search', [SearchEntityController::class, 'poSearch']);
            Route::get('/booking-no-search', [SearchEntityController::class, 'bookingNoSearch']);
            Route::get('{planningInfo}/show', [PlanningInfoController::class, 'show']);
            Route::get('/type/{programId}', [PlanningInfoController::class, 'getProgramType']);
            Route::get('/program-color-preview/{planningInfo}', [PlanningInfoController::class, 'getProgramColorPreview']);
        });
        // yarn allocation
        Route::group(['prefix' => 'yarn-allocation'], function () {
            Route::get('get-bookings', [YarnAllocationController::class, 'getBookings']);
            Route::get('booking-details', [YarnAllocationController::class, 'bookingDetails']);
            Route::post('store', [YarnAllocationController::class, 'store']);
            Route::post('store-details', [YarnAllocationController::class, 'storeDetails']);
            Route::get('search-filter-data', [YarnAllocationController::class, 'searchFilterData']);
            Route::get('/{id}', [YarnAllocationController::class, 'edit']);
            Route::post('store-breakdowns', [YarnAllocationController::class, 'storeBreakdowns']);
            Route::get('get-stock-by-lot/{lot}', [YarnAllocationController::class, 'getYarnStockByLot']);
        });
        Route::get('get-yarn-allocation-detail/{programId}/{colorId}', [YarnAllocationController::class, 'getYarnAllocationDetail']);

        Route::group(['prefix' => 'knit-card'], function () {
            Route::get('/{id}', [KnitCardController::class, 'edit']);
            Route::get('/', [KnittingProductionPlanningController::class, 'getKnitCard']);
            Route::post('/production-planning-assign', [KnittingProductionPlanningController::class, 'assignMachine']);
            Route::post('/production-status-change', [KnittingProductionPlanningController::class, 'changeProductionStatus']);
        });

        // yarn stock list
        Route::get('yarn-stock-info', [YarnRequisitionController::class, 'yarnStockSummary']);
        Route::get('allocation-yarn-stock-info', [ProgramController::class, 'yarnStockSummaryForAllocation']);

        // yarn requisition
        Route::group(['prefix' => 'yarn-requisition'], function () {
            Route::get('search-filter', [YarnRequisitionController::class, 'requisitionSearchFilters']);
            Route::post('search', [YarnRequisitionController::class, 'requisitionSearchData']);
            Route::get('/{programId}', [YarnRequisitionController::class, 'show']);
            Route::post('/', [YarnRequisitionController::class, 'store']);
            Route::delete('/{requisition}', [YarnRequisitionController::class, 'delete']);
            Route::get('/{id}/view', [YarnRequisitionController::class, 'view']);
            Route::get('check-program/{id}', [YarnRequisitionController::class, 'checkIfProgramExists']);
            Route::post('/store-details', [YarnRequisitionController::class, 'storeDetails']);
        });

        Route::group(['prefix' => 'program'], function () {
            Route::get('/', [ProgramApiController::class, '__invoke']);
            Route::post('', [ProgramController::class, 'store']);
            Route::get('{knittingProgram:program_no}/edit', [ProgramController::class, 'edit']);
            Route::delete('/yarn-allocation/{allocationId}/delete', [ProgramController::class, 'yarnAllocationDelete']);
            Route::put('{knittingProgram:program_no}/update-for-fleece', [ProgramController::class, 'updateFleece']);
            Route::get('/machines', [MachineSearchApiController::class, '__invoke']);
            Route::get('/knitting-floor', [KnittingFloorApiController::class, '__invoke']);
            Route::post('{knittingProgram}/machines/store', [ProgramMachineController::class, 'store']);
            Route::get('{knittingProgram}/machines', [ProgramMachineController::class, 'show']);
            Route::delete('/machines/{knittingProgramMachine}/delete', [ProgramMachineController::class, 'destroy']);
            Route::post('yarn-allocate',[ProgramController::class, 'allocateStore']);
            Route::get('get-delete-permission',[ProgramController::class, 'deletePermission']);
            Route::post('get-existing-allocation', [ProgramController::class, 'getExistingAllocation']);

            // collar-cuff-details
            Route::get('{knittingProgram:program_no}/collar-cuff-details', [ProgramCollarCuffDetailsController::class, 'index']);
            Route::post('{knittingProgram:program_no}/collar-cuff-details', [ProgramCollarCuffDetailsController::class, 'store']);

            // stripe details
            Route::get('{knittingProgram:program_no}/stripe-details', [ProgramStripeDetailsController::class, 'index']);
            Route::post('{knittingProgram:program_no}/stripe-details', [ProgramStripeDetailsController::class, 'store']);

            Route::group(['prefix' => 'fabric-color-form'], function () {
                Route::get('/{knit_program_id}/{plan_info_id}', [ProgramFabricColorController::class, 'getData']);
                Route::post('/{knit_program_id}/{plan_info_id}', [ProgramFabricColorController::class, 'saveData']);
            });
            Route::get('/program-color-preview/{knittingProgram}', [ProgramController::class, 'getProgramColorPreview']);
        });

        Route::group(['prefix' => 'knitting-production'], function () {
            Route::get('/search-knit-program', [KnittingProgramSearchApiController::class, '__invoke']);
            Route::get('/search-knit-card', [KnitCardSearchApiController::class, '__invoke']);
            Route::get('/knit-program-collar-cuff', [CollarCuffSearchApiController::class, '__invoke']);
            Route::get('/{knitCardId}/knitting-rolls', [KnittingProductionController::class, 'show']);
            Route::delete('/{knitProgramRoll}/delete', [KnittingProductionController::class, 'delete']);
            Route::post('', [KnittingProductionController::class, 'store']);
        });

        Route::group(['prefix' => 'knitting-qc'], function () {
            Route::get('/grade-calculate', [GradePointApiController::class, '__invoke']);
            Route::get('/{rollId}', [KnittingQcController::class, 'getKnitQcFormData']);
        });
    });


    Route::group(['prefix' => 'api/v1/common'], function () {
        Route::get('suppliers', [CommonAPIController::class, 'suppliers']);
        Route::get('machine-types', [CommonAPIController::class, 'getMachineType']);
        Route::get('fabric-descriptions', [CommonAPIController::class, 'fabricDescriptions']);
        Route::get('gmt-size', [CommonAPIController::class, 'getGmtSize']);
        Route::get('get-default-uom/{uom}', [CommonAPIController::class, 'getDefaultUOM']);
        Route::get('get-default-process/{process}', [CommonAPIController::class, 'getDefaultProcess']);
        Route::get('/get-default-color-range/{colorRange}', [CommonAPIController::class, 'getDefaultColorRange']);
        Route::get('get-buyer-for-within-status-no', [CommonAPIController::class, 'getBuyerFoStorerWithinStatusNo']);
        Route::get('yarn-counts', [CommonAPIController::class, 'getYarnCounts']);
        Route::get('get-sales-order-nos-by-buyer/{buyerId}', [CommonAPIController::class, 'salesOrderNosByBuyer']);
        Route::get('auth-factory-id', function () {
            return factoryId();
        });
    });

    Route::group(['prefix' => 'buyer-style-report'], function(){
        Route::get('/',[BuyerStyleReportController::class,'index']);
        Route::get('/buyer-wise-style',[BuyerStyleReportController::class,'getStyle']);
        Route::get('/get-report',[BuyerStyleReportController::class,'getReport']);
        Route::get('/buyer-style-pdf', [BuyerStyleReportController::class, 'pdf']);
        Route::get('/buyer-style-excel', [BuyerStyleReportController::class, 'excel']);
    });


});
