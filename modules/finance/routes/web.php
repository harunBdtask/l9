<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Finance\Controllers\CommonApiController;
use SkylarkSoft\GoRMG\Finance\Controllers\ControlAccountController;
use SkylarkSoft\GoRMG\Finance\Controllers\CustomerBillPaymentController;
use SkylarkSoft\GoRMG\Finance\Controllers\GroupAccountController;
use SkylarkSoft\GoRMG\Finance\Controllers\LedgerAccountController;
use SkylarkSoft\GoRMG\Finance\Controllers\ParentAccountController;
use SkylarkSoft\GoRMG\Finance\Controllers\PurposeController;
use SkylarkSoft\GoRMG\Finance\Controllers\SubLedgerAccountController;
use SkylarkSoft\GoRMG\Finance\Controllers\SupplierBillPaymentController;
use SkylarkSoft\GoRMG\Finance\Controllers\SupplierController;
use SkylarkSoft\GoRMG\Finance\Controllers\VoucherApprovePanelController;

Route::group([
    'middleware' => ['web', 'auth', 'menu-auth'],
    'prefix' => 'finance',
    'namespace' => 'SkylarkSoft\GoRMG\Finance\Controllers'
], function () {
    Route::get('/accounts', 'AccountsController@index');
    Route::get('/fetch-account-list', 'AccountsController@getAccountList');
    Route::post('/accounts', 'AccountsController@store');
    Route::get('/accounts/create', 'AccountsController@create');
    Route::get('/accounts/{id}/edit', 'AccountsController@edit');
    Route::put('/accounts/{id}', 'AccountsController@update');
    Route::get('/generate-code', 'AccountsController@generateAccountCode');
    // voucher entry
    Route::get('/vouchers/entry', 'VouchersController@entry');

    Route::get('/vouchers', 'VouchersController@index');
    Route::post('/vouchers', 'VouchersController@store');
    Route::put('/vouchers_all/{id}', 'VouchersController@update');
    Route::post('/vouchers_all', 'VouchersController@store_all');
    Route::post('/voucher_store_and_copy', 'VouchersController@storeAndCopy');

    Route::get('/vouchers/create', 'VouchersController@create');
    Route::get('/vouchers/{id}', 'VouchersController@show');
    Route::get('/vouchers/{id}/edit', 'VouchersController@edit');
    Route::put('/vouchers/{id}', 'VouchersController@update');
    Route::put('/vouchers/{id}/approval', 'VouchersController@approval');
    Route::get('/vouchers/{id}/print', 'VouchersController@print');
    Route::get('/vouchers/{companyId}/fetch-units', 'VouchersController@getUnits');
    Route::get('/vouchers/{companyId}/{unitId}/fetch-departments', 'VouchersController@getDepartments');

    Route::get('/ledger', 'FinancialReportsController@ledger');
    Route::get('/trial-balance', 'FinancialReportsController@trialBalance');
    Route::get('/receipts-and-payments', 'FinancialReportsController@receiptsAndPayments');
    Route::get('/income-statement', 'FinancialReportsController@incomeStatement');
    Route::get('/balance-sheet', 'FinancialReportsController@balanceSheet');
    Route::get('/cash-flow-statement', 'FinancialReportsController@cashFlowStatement');
    Route::get('/transactions', 'FinancialReportsController@transactions');
    Route::get('/ledger-v2', 'FinancialReportsController@ledgerReportV2');
    Route::get('/ledger-voucher-details', 'FinancialReportsController@voucherDetails');
    Route::get('/trial-balance-v2', 'FinancialReportsController@trialBalanceV2');
    Route::get('vouchers-approve-panels', [VoucherApprovePanelController::class, 'index']);

    // Bank Account...
    Route::resource('banks', 'BankController');
    Route::resource('bank-accounts', 'BankAccountController');
    Route::resource('/bank-contact-details', 'BankContactController');
    Route::get('get-bank-short-name/{bank}', 'BankAccountController@bankShortName');
    Route::resource('/cheque-books', 'ChequeBookController');
    Route::get('cheque-books/{chequeBook}/view', 'ChequeBookController@view');
    Route::delete('/cheque-books/{chequeBook}/delete', 'ChequeBookController@destroy');
    Route::put('/cheque-book-details/{detail}', 'ChequeBookController@detailUpdate');
    Route::get('/cheque-clear', 'ChequeBookController@clearFormView');
    Route::post('/multiple-cheque-clearing', 'ChequeBookController@multipleChequeClearing');
    Route::get('/clear-cheque-list', 'ChequeBookController@clearChequeList');
    Route::get('/unclear-cheque-list', 'ChequeBookController@unclearChequeList');

    Route::resource('/supplier-bill-entry', 'SupplierBillEntryController');
    Route::get('/supplier-bill-entry/{id}/edit-data', 'SupplierBillEntryController@getEditData');
    Route::get('/supplier-bill-entry/{billEntry}/view', 'SupplierBillEntryController@view');
    Route::resource('/supplier-bill-payment', 'SupplierBillPaymentController');
    Route::get('/supplier-bill-payment/{id}/edit-data', 'SupplierBillPaymentController@getEditData');
    Route::post('/store_job_number', 'SupplierBillEntryController@store_job_number');

    Route::group(['prefix' => 'supplier'], function(){
        Route::post('/createJVPost', 'SupplierBillEntryController@createJVPost');
        Route::post('/billPaymentJVPost', 'SupplierBillPaymentController@billPaymentJVPost');
    });
    
    Route::group(['prefix' => 'customer'], function(){
        Route::post('/createInvoiceJVPost', 'CustomerBillEntryController@createInvoiceJVPost');
        Route::post('/billReceiveJVPost', 'CustomerBillPaymentController@billReceiveJVPost');
    });


    // Customer Management
    Route::resource('/customer-bill-entry', 'CustomerBillEntryController');
    Route::get('/customer-bill-entry/{billEntry}/view', 'CustomerBillEntryController@view');
    Route::get('/customer-bill-entry/buyer-dyeing-processes/{buyer}', 'CustomerBillEntryController@getBuyerDyeingProcesses');
    Route::resource('/customer-bill-payment', 'CustomerBillPaymentController');

    // Routes for ac-company
    Route::resource('ac-companies', 'AcCompanyController');

    // Routes for ac unit which is project
    Route::resource('ac-projects', 'AcUnitController');

    // Routes for ac department as cost center
    Route::resource('ac-cost-centers', 'AcDepartmentController');

    //Routes of department
    Route::resource('ac-departments', 'AcActualDepartmentController');

    // Suppliers...
    Route::get('suppliers', [SupplierController::class, 'index']);
    Route::get('suppliers/create', [SupplierController::class, 'create']);
    Route::post('suppliers', [SupplierController::class, 'store']);
    Route::get('suppliers/{id}/edit', [SupplierController::class, 'create']);
    Route::get('suppliers/{supplier}/edit-data', [SupplierController::class, 'edit']);
    Route::put('suppliers/{supplier}', [SupplierController::class, 'update']);
    Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy']);
    Route::get('suppliers/{supplier}/remove-file', [SupplierController::class, 'removeFile']);
    Route::delete('suppliers/{item}/delete-item', [SupplierController::class, 'destroyItem']);

    //Fund Requisition
    Route::group(['prefix' => 'fund-requisition'], function () {
        Route::get('/purposes', [PurposeController::class, 'index']);
        Route::get('/purposes/search', [PurposeController::class, 'search']);
        Route::post('/purposes', [PurposeController::class, 'store']);
        Route::get('/purposes/{id}/edit', [PurposeController::class, 'edit']);
        Route::put('/purposes/{purpose}', [PurposeController::class, 'update']);
        Route::delete('/purposes/{purpose}/delete', [PurposeController::class, 'destroy']);

        //Report
        Route::get('reports', 'FundRequisitionReportController@index');
        Route::get('reports/{id}', 'FundRequisitionReportController@view');

        //Account Approval
        Route::get('account-approval', 'AccountApprovalController@index');
        Route::get('account-approval/create', 'AccountApprovalController@create');
        Route::post('account-approval', 'AccountApprovalController@store');
        Route::get('account-approval/{id}', 'AccountApprovalController@view');
        Route::get('account-approval/print/{id}', 'AccountApprovalController@print');

        //Audit Approval
        Route::get('audit-approval', 'AuditApprovalController@index');
        Route::get('audit-approval/create', 'AuditApprovalController@create');
        Route::post('audit-approval', 'AuditApprovalController@store');
        Route::get('audit-approval/{id}', 'AuditApprovalController@view');
        Route::get('audit-approval/print/{id}', 'AuditApprovalController@print');

        Route::get('', 'FundRequisitionController@index');
        Route::post('', 'FundRequisitionController@store');
        Route::get('create', 'FundRequisitionController@create');
        Route::get('{id}', 'FundRequisitionController@view');
        Route::get('{id}/print', 'FundRequisitionController@print');
        Route::delete('{id}', 'FundRequisitionController@destroy');
        Route::post('{id}/approve', 'FundRequisitionController@approve');
    });

    Route::group(['prefix' => 'api/v1'], function () {
        Route::get('/get-parent-account', [ParentAccountController::class, 'index']);
        Route::get('/get-parent-account-code', [ParentAccountController::class, 'accountCode']);
        Route::post('/save-parent-account', [ParentAccountController::class, 'store']);

        Route::get('/get-group-account', [GroupAccountController::class, 'index']);
        Route::get('/get-group-account-code', [GroupAccountController::class, 'accountCode']);
        Route::post('/save-group-account', [GroupAccountController::class, 'store']);

        Route::get('/get-control-account', [ControlAccountController::class, 'index']);
        Route::get('/get-control-account-code', [ControlAccountController::class, 'accountCode']);
        Route::post('/save-control-account', [ControlAccountController::class, 'store']);

        Route::get('/get-ledger-account', [LedgerAccountController::class, 'index']);
        Route::get('/get-ledger-account-code', [LedgerAccountController::class, 'accountCode']);
        Route::post('/save-ledger-account', [LedgerAccountController::class, 'store']);
        Route::put('/update-ledger-account/{account}', [LedgerAccountController::class, 'update']);

        Route::get('/get-sub-ledger-account', [SubLedgerAccountController::class, 'index']);
        Route::post('/save-sub-ledger-account', [SubLedgerAccountController::class, 'store']);
        Route::delete('/delete-account/{account}', [ParentAccountController::class, 'destroy']);

        Route::get("/get-units", [CommonApiController::class, 'fetchUnit']);
        Route::get('/fetch-project-wise-units/{companyId}/{projectId}', [CommonApiController::class, 'projectWiseUnit']);

        Route::get("/get-departments/{id}", [CommonApiController::class, 'fetchDepartment']);

        Route::get("/requisition-no", [CommonApiController::class, 'getRequisitionNo']);
        Route::get('/get-account-types', [CommonApiController::class, 'getAccountTypes']);
        Route::get('/get-account-type-short-form/{id}', [CommonApiController::class, 'getAccountTypeShortForm']);
        Route::get('/get-control-ledgers', [CommonApiController::class, 'getControlLedgers']);

        Route::get('/get-voucher-type-wise-info/{voucher_type}', [CommonApiController::class, 'getVoucherInfo']);
        Route::get('/get-ledger-account-by-control-ac/{id}', [CommonApiController::class, 'getLedgerAccounts']);
        Route::get('/get-ledger-account-list-by-control-ac/{id}', [CommonApiController::class, 'getLedgerAccountsList']);

        Route::get('/update-cheque-details/{chequeId}/{to}/{amount}/{trnDate}/{dueDate}', [CommonApiController::class, 'updateChequeBookDetails']);

        Route::get("/fetch-company", [CommonApiController::class, 'fetchCompany']);
        Route::get("/fetch-groups", [CommonApiController::class, 'fetchGroups']);
        Route::get("/fetch-currency", [CommonApiController::class, 'fetchCurrency']);
        Route::get("/fetch-suppliers", [CommonApiController::class, 'fetchSuppliers']);
        Route::get("/fetch-uom", [CommonApiController::class, 'fetchUom']);
        Route::get("/fetch-vat-info", [CommonApiController::class, 'fetchVatInfo']);
        Route::get("/fetch-pay-modes", [CommonApiController::class, 'fetchPayModes']);
        Route::get("/fetch-all-ledger-accounts", [CommonApiController::class, 'fetchAllLedgerAccounts']);
        Route::get("/fetch-job-numbers", [CommonApiController::class, 'fetchJobNumbers']);

        Route::get("/fetch-proforma-invoice", [CommonApiController::class, 'fetchProformaInvoice']);
        Route::get("/get-proforma-invoice/{pi}", [CommonApiController::class, 'getProformaInvoice']);
        Route::get("/fetch-item-groups", [CommonApiController::class, 'fetchItemGroups']);
        Route::get("/get-item-group_info/{id}", [CommonApiController::class, 'fetchItemGroupInfo']);

        Route::get("/fetch-bill-numbers", [SupplierBillPaymentController::class, 'fetchBillNumbers']);
        Route::post("/fetch-supplier-bill", [SupplierBillPaymentController::class, 'fetchSupplierBill']);
        Route::get("/fetch-customer-bill-numbers", [CommonApiController::class, 'fetchCustomerBillNumbers']);
        Route::get("/fetch-customer-bills", [CustomerBillPaymentController::class, 'fetchCustomerBills']);

        Route::get('/fetch-buyer-by-id/{buyer}', 'CommonApiController@fetchBuyerById');

        Route::group(['namespace' => 'Api'], function () {
            Route::get('/get-purposes', 'PurposesApiController');
            Route::get('/get-banks', 'BanksApiController');
            Route::get('/get-bank-accounts/{bankId}', 'BankAccountsApiController');
            Route::get('/get-cheque-no/{bankAccountId}', 'ChequeNoApiController');
            Route::get('/get-pay-mode-wise-accounts/{payMode}', 'PayModeWiseAccounts');
            Route::get('/fetch-control-accounts', 'ControlAccountsApiController');
            Route::get('/get-item-groups', 'ItemGroupsApiController');
            Route::get('/fetch-ledger-accounts/{controlAccountId}', 'LedgerAccountsApiController');
            Route::get('/fetch-company-wise-projects/{companyId}', 'CompanyWiseProjectApiController@getProjects');
            Route::get('/fetch-project-wise-users/{companyId}/{projectId}', 'ProjectWiseUsersApiController@getProjectWiseUsers');
        });
    });

    // Routes for project
    Route::resource('/projects', 'ProjectController');

    // Routes for department
    Route::resource('/departments', 'DepartmentController');

    // Routes for cost center
    Route::resource('/cost-centers', 'CostCenterController');

    // Routes for unit
    Route::resource('/units', 'UnitController');

    Route::post('/save-account-types', 'AccountTypeController@store');

    // Receive Banks
    Route::resource('/receive-banks', 'ReceiveBankController');

    // Receive Cheques
    Route::resource('/receive-cheques', 'ReceiveChequeController');
});
