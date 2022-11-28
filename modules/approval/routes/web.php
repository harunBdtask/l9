<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Approval\Controllers\BudgetApprovalController;
use SkylarkSoft\GoRMG\Approval\Controllers\CuttingQtyApprovalController;
use SkylarkSoft\GoRMG\Approval\Controllers\EmbellishmentApprovalController;
use SkylarkSoft\GoRMG\Approval\Controllers\FabricBookingApprovalController;
use SkylarkSoft\GoRMG\Approval\Controllers\GatePassChallanApprovalController;
use SkylarkSoft\GoRMG\Approval\Controllers\OrderApprovalController;
use SkylarkSoft\GoRMG\Approval\Controllers\PermissionController;
use SkylarkSoft\GoRMG\Approval\Controllers\PoApprovalController;
use SkylarkSoft\GoRMG\Approval\Controllers\PrintSendChallanCutManagerController;
use SkylarkSoft\GoRMG\Approval\Controllers\ServiceBookingApprovalController;
use SkylarkSoft\GoRMG\Approval\Controllers\SewingInutChallanCutManagerController;
use SkylarkSoft\GoRMG\Approval\Controllers\ShortFabricBookingApprovalController;
use SkylarkSoft\GoRMG\Approval\Controllers\ShortTrimsBookingApprovalController;
use SkylarkSoft\GoRMG\Approval\Controllers\TrimsBookingApprovalController;
use SkylarkSoft\GoRMG\Approval\Controllers\YarnPurchaseApprovalController;
use SkylarkSoft\GoRMG\Approval\Controllers\YarnStoreApprovalController;
use SkylarkSoft\GoRMG\Approval\Controllers\DyesChemicalStoreApprovalController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets\API\BudgetApprovalApiController;
use SkylarkSoft\GoRMG\Merchandising\Controllers\PriceQuotation\PriceQuotationApprovalController;

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'namespace' => 'SkylarkSoft\GoRMG\Approval\Controllers'], function () {
    Route::group(['prefix' => 'approvals'], function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::get('/user-approval-permission', [PermissionController::class, 'index']);
        Route::get('/user-approval-permission/search', [PermissionController::class, 'index']);
        Route::get('/fetch-permission-pages', [PermissionController::class, 'fetchPermissionPages']);
        Route::get('/create', [PermissionController::class, 'create']);
        Route::post('/save', [PermissionController::class, 'store']);
        Route::get('/{id}/get', [PermissionController::class, 'get']);
        Route::delete('/details/{id}', [PermissionController::class, 'deleteDetails']);
        Route::delete('/{id}', [PermissionController::class, 'destroy']);

        Route::group(['prefix' => 'modules'], function () {
            Route::resource('/price-quotation', 'PriceQuotationApprovalController');
            Route::post('/price-quotation/cancel-and-rework', [PriceQuotationApprovalController::class, 'cancelAndRework']);
            Route::resource('/budget', 'BudgetApprovalController');
            Route::post('/budget/cancel-and-rework', [BudgetApprovalController::class, 'cancelAndRework']);
            Route::get('/po-approval-for-budget', 'PoApprovalForBudgetApiController');
            Route::get('/poApproval', [PoApprovalController::class, 'index']);
            Route::post('/poApproval', [PoApprovalController::class, 'store']);
            Route::get('/po-unapproved-request-data', [PoApprovalController::class, 'getUnapprovedData']);
            Route::post('/po-unapproved-request-data', [PoApprovalController::class, 'updateApprovedStatus']);
            Route::get('/fetch-budget-unique/{buyerId}', [BudgetApprovalController::class, 'getBudgetUnique']);
            Route::get('/fetch-budget-style/{buyerId}', [BudgetApprovalController::class, 'getBudgetStyle']);

            Route::get('/unapproved-bookings', [FabricBookingApprovalController::class, 'unApprovedRequests']);
            Route::put('/un-approve', [FabricBookingApprovalController::class, 'unApprove']);
            Route::resource('/fabric-booking', 'FabricBookingApprovalController');
            Route::get('/fabric-booking/status/{id}', 'FabricBookingApprovalController@checkApprovalStatus');
            Route::post('/fabric-booking/cancel-and-rework', 'FabricBookingApprovalController@cancelAndRework');
            Route::resource('/trims-booking', 'TrimsBookingApprovalController');
            Route::resource('/gate-pass-challan', 'GatePassChallanApprovalController');
            Route::get('/unapproved-get-pass-challans', [GatePassChallanApprovalController::class, 'unApprovedRequests']);
            Route::post('/gate-pass-challan/un-approve', [GatePassChallanApprovalController::class, 'unApprove']);
            Route::get('/unapproved-trims-bookings', [TrimsBookingApprovalController::class, 'unApprovedRequests']);
            Route::put('/trims-booking-un-approve', [TrimsBookingApprovalController::class, 'unApprove']);
            Route::get('/trims-booking/status/{id}', [TrimsBookingApprovalController::class, 'checkApprovalStatus']);
            Route::post('/trims-booking/cancel-and-rework', [TrimsBookingApprovalController::class, 'cancelAndRework']);

            // yarn store approval
            Route::resource('/yarn-store', 'YarnStoreApprovalController');
            Route::get('/unapproved-yarn-receives', [YarnStoreApprovalController::class, 'unApprovedRequests']);
            Route::post('/yarn-store/un-approve', [YarnStoreApprovalController::class, 'unApprove']);

            // dyes chemical store approval
            Route::resource('/dyes-chemical-store', 'DyesChemicalStoreApprovalController');
            Route::get('/unapproved-dyes-chemical-receives', [DyesChemicalStoreApprovalController::class, 'unApprovedRequests']);
            Route::post('/dyes-chemical-store/un-approve', [DyesChemicalStoreApprovalController::class, 'unApprove']);

            //
            Route::resource('/service-booking', 'ServiceBookingApprovalController');
            Route::post('/service-booking/cancel-and-rework', [ServiceBookingApprovalController::class, 'cancelAndRework']);
            Route::get('/unapproved-service-bookings', [ServiceBookingApprovalController::class, 'unApprovedRequests']);
            Route::put('/service-booking-un-approve', [ServiceBookingApprovalController::class, 'unApprove']);

            Route::resource('/embellishment', 'EmbellishmentApprovalController');
            Route::get('/unapproved-embellishment', [EmbellishmentApprovalController::class, 'unApprovedRequests']);
            Route::put('/embellishment-un-approve', [EmbellishmentApprovalController::class, 'unApprove']);

            Route::resource('/yarn-purchase', 'YarnPurchaseApprovalController');
            Route::get('/unapproved-yarn-purchase', [YarnPurchaseApprovalController::class, 'unApprovedRequests']);
            Route::put('/yarn-purchase-un-approve', [YarnPurchaseApprovalController::class, 'unApprove']);
            Route::get('/yarn-purchase/status/{id}', [YarnPurchaseApprovalController::class, 'checkApprovalStatus']);
            Route::post('/yarn-purchase/cancel-and-rework', [YarnPurchaseApprovalController::class, 'cancelAndRework']);

            Route::get('/unapproved-short-fabric-bookings', [ShortFabricBookingApprovalController::class, 'unApprovedRequests']);
            Route::put('/un-approve-short-fabric-booking', [ShortFabricBookingApprovalController::class, 'unApprove']);
            Route::resource('/short-fabric-booking', 'ShortFabricBookingApprovalController');

            Route::resource('/short-yarn-purchase', 'ShortTrimsBookingApprovalController');
            Route::get('/unapproved-short-trims-bookings', [ShortTrimsBookingApprovalController::class, 'unApprovedRequests']);
            Route::put('/short-trims-booking-un-approve', [ShortTrimsBookingApprovalController::class, 'unApprove']);

            Route::prefix('print-send-challan-cut-manager')->group(function () {
                Route::get('/', [PrintSendChallanCutManagerController::class, 'index']);
                Route::get('/create', [PrintSendChallanCutManagerController::class, 'create']);
                Route::post('/', [PrintSendChallanCutManagerController::class, 'store']);
            });

            Route::prefix('sewing-input-challan-cut-manager')->group(function () {
                Route::get('/', [SewingInutChallanCutManagerController::class, 'index']);
                Route::get('/create', [SewingInutChallanCutManagerController::class, 'create']);
                Route::post('/', [SewingInutChallanCutManagerController::class, 'store']);
            });

            Route::group(['prefix' => 'order-approval'], function () {
                Route::get('', [OrderApprovalController::class, 'index']);
                Route::get('search', [OrderApprovalController::class, 'search']);
                Route::post('', [OrderApprovalController::class, 'store']);
                Route::get('status/{id}', [OrderApprovalController::class, 'orderApprovalStatus']);
                Route::get('request-remove-approvals', [OrderApprovalController::class, 'requestRemoveApprovals']);
                Route::post('request-remove-approvals', [OrderApprovalController::class, 'updateRequestRemoveApprovals']);
                Route::post('cancel-and-rework', [OrderApprovalController::class, 'cancelAndRework']);
                Route::post('store-pcd', [OrderApprovalController::class, 'storePcd']);
            });

            Route::prefix('cutting-qty')->group(function () {
                Route::get('/', [CuttingQtyApprovalController::class, 'index'])->name('cutting-qty-approval');
                Route::post('/{cuttingQtyRequest}', [CuttingQtyApprovalController::class, 'store']);
                Route::post('/reject/{cuttingQtyRequest}', [CuttingQtyApprovalController::class, 'rejectRequest']);
            });
        });

    });
});


Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'namespace' => 'SkylarkSoft\GoRMG\Merchandising\Controllers'], function () {
    Route::get('/fetch-unapproved-request-data-pq', [PriceQuotationApprovalController::class, 'getUnapprovedData']);
    Route::put('/update-approve-status-pq', [PriceQuotationApprovalController::class, 'updateApprovedStatus']);
    Route::get('/fetch-unapproved-request-data-budget', [BudgetApprovalApiController::class, 'getUnapprovedData']);
    Route::put('/update-approve-status-budget', [BudgetApprovalApiController::class, 'updateApprovedStatus']);
});

Route::get('/test-menu', function () {
    return session('menu');
});

Route::get('/flush', function () {
    session()->flush();

    return redirect('/');
});
