<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Procurement\Controllers\API\CommonApiController;
use SkylarkSoft\GoRMG\Procurement\Controllers\PurchaseOrderController;
use SkylarkSoft\GoRMG\Procurement\Controllers\QuotationController;
use SkylarkSoft\GoRMG\Procurement\Controllers\RequisitionController;

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'prefix' => 'procurement'], function () {
    // requisitions
    Route::group(['prefix' => 'requisitions'], function () {
        Route::get('/', [RequisitionController::class, 'index']);
        Route::post('/', [RequisitionController::class, 'store']);
        Route::get('/view/{procurementRequisition}', [RequisitionController::class, 'show']);
        Route::get('/{procurementRequisition}/edit', [RequisitionController::class, 'edit']);
        Route::get('/view/{procurementRequisition}/pdf', [RequisitionController::class, 'pdf']);
        Route::put('/{procurementRequisition}', [RequisitionController::class, 'update']);
        Route::delete('/{procurementRequisition}', [RequisitionController::class, 'destroy']);
        Route::get('/{any?}', [RequisitionController::class, 'create'])
                ->where('any', '.*');
    });
    //Quotations
    Route::resource('quotations', QuotationController::class);

    //Purchase Order
    Route::resource('purchase-order', PurchaseOrderController::class);
    Route::delete('/purchase-order/po_details_delete/{id}', [PurchaseOrderController::class, 'po_details_delete']);

    // APIs
    Route::group(['prefix' => '/api/v1'], function () {
        Route::get('/get-departments', [CommonApiController::class, 'getDepartments']);
        Route::get('/get-users', [CommonApiController::class, 'getUsers']);
        Route::get('/get-uoms', [CommonApiController::class, 'getUoms']);
        Route::get('/get-suppliers', [CommonApiController::class, 'getSuppliers']);
        Route::get('/get-item-groups', [CommonApiController::class, 'getItemGroups']);
        Route::get('/fetch-requisitions', [CommonApiController::class, 'fetchRequisitions']);
        Route::get('/fetch-requisition-items/{id}', [CommonApiController::class, 'fetchRequisitionItems']);
        Route::get('/fetch-quotation-description/{supplier_id}/{item_id}', [CommonApiController::class, 'fetchQuotationDescription']);
    });
});
