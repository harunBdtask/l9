<?php

use SkylarkSoft\GoRMG\BasicFinance\Controllers\PurposeController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\AccountsController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\VouchersController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\ChequeBookController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\UnitAPIController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\BankAccountController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\LoanAccountController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\BanksApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\ItemsApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\CommonApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\PayModeWiseAccounts;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\CompanyApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\AccountsAPIController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\ChequeNoApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\PurposesApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\ItemTypesApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\FinancialReportsController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\AccountsAPIControllerV2;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\AccountCodeApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\AccountTypeApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\DepartmentsApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\BalanceSheetReportController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\BankAccountsApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\ReceiveBanksApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\VoucherApprovePanelController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\CreatedByNameApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\ItemCategoriesApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\ProjectWiseUnitApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\ProjectWiseUsersApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\TypeWiseAccountsApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\CashInHadAccountsApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\DeductionAccountsAPIController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\CompanyWiseProjectApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\ParentBankDebitAccApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\ChequeDetailsUpdateApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\CompanyWiseAccountsApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\AccountTypeShortFormApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\DistributionAccountsAPIController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\TrialBalanceAccountsAPIController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\TypeWiseParentAccountApiController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\LoanDistributionAccountsAPIController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\ForeignBankChargeAccountsAPIController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\Reports\ProjectWiseCashBookReportController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\AccountingRealization\RealizationReportController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\AccountingRealization\AccountingRealiationController;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\AccountingRealization\RealizationToVoucherController;

Route::group([
    'middleware' => ['web', 'auth', 'menu-auth'],
    'prefix' => 'basic-finance',
    'namespace' => 'SkylarkSoft\GoRMG\BasicFinance\Controllers'
], function () {

    Route::get('/maintenance', function () {
        return view('basic-finance::pages.under_maintenance_page');
    });

    // Routes for company
    Route::resource('/companies', 'CompanyController');

    // Routes for project
    Route::resource('/projects', 'ProjectController');

    // Routes for unit
    Route::resource('/units', 'UnitController');

    // Routes for department
    Route::resource('/departments', 'DepartmentController');

    // Routes for cost center
    Route::resource('/cost-centers', 'CostCenterController');

    // Routes for accounts
    Route::group(['prefix' => '/accounts'], function () {
        Route::get('/', [AccountsController::class, 'index']);
        Route::get('/v2', [AccountsController::class, 'index2']);
        Route::post('/', [AccountsController::class, 'store']);
        Route::get('/create', [AccountsController::class, 'create']);
        Route::get('/{id}/edit', [AccountsController::class, 'edit']);
        Route::put('/{account}', [AccountsController::class, 'update']);
    });
    Route::get('/fetch-account-types', 'AccountsController@fetchAccountTypes');
    Route::get('/fetch-accounts', 'AccountsController@fetchAccounts');

    // Routes for Banks
    Route::resource('/banks', 'BankController');
    Route::resource('/bank-contract-details', 'BankContractDetailController');
    Route::resource('/bank-accounts', 'BankAccountController');
    Route::get('/get-bank-short-name/{bank}', [BankAccountController::class, 'bankShortName']);
    Route::resource('/cheque-books', 'ChequeBookController');
    Route::get('cheque-books/{chequeBook}/view', 'ChequeBookController@view');
    Route::get('/cheque-clear', 'ChequeBookController@clearFormView');
    Route::get('/clear-cheque-list', 'ChequeBookController@clearChequeList');
    Route::get('/unclear-cheque-list', 'ChequeBookController@unclearChequeList');
    Route::post('/multiple-cheque-clearing', [ChequeBookController::class, 'multipleChequeClearing']);
    Route::delete('/cheque-books/{chequeBook}/delete', 'ChequeBookController@destroy');
    Route::put('/cheque-book-details/{detail}', 'ChequeBookController@detailUpdate');

    //Loan Routes
    Route::group(['prefix' => '/loan'], function () {
        Route::group(['prefix' => '/accounts'], function () {
            Route::resource('/', 'LoanAccountController');
            Route::get('/{loanAccount}/show', 'LoanAccountController@show');
            Route::get('/{loanAccount}/edit', 'LoanAccountController@create')->name('loan_account_edit');
            Route::put('/{loanAccount}', 'LoanAccountController@update');
            Route::delete('/{loanAccount}', 'LoanAccountController@destroy');
            //  Route::get('{any?}', [LoanAccountController::class, 'create'])->where('any', '.*');
        });
        //        Route::get("/report", "DevelopmentFabricInventoryControlController@reportView");
    });


    // Routes for vouchers
    Route::group(['prefix' => 'vouchers'], function () {
        Route::get('/', [VouchersController::class, 'index']);
        Route::post('/', [VouchersController::class, 'store']);
        Route::post('/store-and-copy', [VouchersController::class, 'storeAndCopy']);
        Route::get('/create', [VouchersController::class, 'create']);
        Route::get('/{id}', [VouchersController::class, 'show']);
        Route::get('/{id}/edit', [VouchersController::class, 'edit']);
        Route::put('/{id}', [VouchersController::class, 'update']);
        Route::put('/{id}/approval', [VouchersController::class, 'approval']);
        Route::get('/{id}/print', [VouchersController::class, 'print']);
        Route::get('/{id}/pdf', [VouchersController::class, 'pdf']);
        Route::get('/{companyId}/fetch-projects', 'VouchersController@getProjects');
        Route::get('/{companyId}/{projectId}/fetch-units', 'VouchersController@getUnits');
        Route::get('/fetch-departments', 'VouchersController@getDepartments');
        Route::get('/fetch-cost-centers', 'VouchersController@getCostCenters');
    });

    Route::post('/multiple-vouchers-posting', [VouchersController::class, 'multipleJournalPosting']);
    Route::get('/ledger', [FinancialReportsController::class, 'ledgerReport']);
    Route::get('/ledger-v3', [FinancialReportsController::class, 'ledgerReportV3']);
    Route::get('/ledger-voucher-details', [FinancialReportsController::class, 'voucherDetails']);
    Route::get('/ledger-v2', [FinancialReportsController::class, 'ledgerReportV2']);
    Route::get('/trial-balance-tree', [TrialBalanceAccountsAPIController::class, 'fetchData']);
    Route::get('/trial-balance', [FinancialReportsController::class, 'trialBalance']);
    Route::get('/trial-balance-v2', [FinancialReportsController::class, 'trialBalanceV2']);
    Route::get('/group-ledger', 'FinancialReportsController@groupLedger');
    Route::get('/income-statement', 'FinancialReportsController@incomeStatement');
    Route::get('/balance-sheet', [BalanceSheetReportController::class, 'fetchData']);
    Route::get('/cash-flow-statement', 'FinancialReportsController@cashFlowStatement');
    Route::get('/transactions', 'FinancialReportsController@transactions');
    Route::get('vouchers-approve-panels', [VoucherApprovePanelController::class, 'index']);
    Route::get('/provisional-ledger', [FinancialReportsController::class, 'provisionalLedger']);

    // Account budgets...
    Route::group(['prefix' => 'budgets'], function () {
        Route::get('/get-budget-details', 'AcBudgetController@getBudgetDetails');
        Route::get('', 'AcBudgetController@index');
        Route::get('/create', 'AcBudgetController@create');
        Route::post('', 'AcBudgetController@store');
        Route::get('/{acBudget}/edit', 'AcBudgetController@edit');
        Route::put('/{acBudget}', 'AcBudgetController@update');
        Route::get('/{acBudget}', 'AcBudgetController@show');
        Route::delete('/{acBudget}/delete', 'AcBudgetController@destroy');
        Route::get('/{acBudget}/view', 'AcBudgetController@view');
    });

    // Account budget approvals...
    Route::group(['prefix' => 'budget-approvals'], function () {
        Route::get('/get-budget-details', 'AcBudgetApprovalController@getBudgetDetails');
        Route::get('', 'AcBudgetApprovalController@index');
        Route::get('/create', 'AcBudgetApprovalController@create');
        Route::get('{id}/view', 'AcBudgetApprovalController@view');
        Route::post('', 'AcBudgetApprovalController@store');
    });

    // Account budget reports...
    Route::get('/budget-month-wise-report', 'AcBudgetMonthWiseReportController@index');
    Route::post('/budget-month-wise-report', 'AcBudgetMonthWiseReportController@reportData');
    Route::get('/budget-month-wise-report-pdf', 'AcBudgetMonthWiseReportController@pdf');
    Route::get('/budget-month-wise-report-excel', 'AcBudgetMonthWiseReportController@excel');

    Route::get('/budget-month-wise-expense-report', 'AcBudgetMonthWiseExpenseReportController@index');
    Route::post('/budget-month-wise-expense-report', 'AcBudgetMonthWiseExpenseReportController@reportData');
    Route::get('/budget-month-wise-expense-report-pdf', 'AcBudgetMonthWiseExpenseReportController@pdf');
    Route::get('/budget-month-wise-expense-report-excel', 'AcBudgetMonthWiseExpenseReportController@excel');

    // Receipt Payment Report...
    Route::get('/all-receipts-and-payments', 'FinancialReportsController@receiptsAndPaymentsAll');
    Route::get('/cash-receipts-and-payments', 'FinancialReportsController@receiptsAndPaymentscash');
    Route::get('/bank-receipts-and-payments', 'FinancialReportsController@receiptsAndPaymentsBank');

    // Month Wise Receipt Payment Report...
    Route::get('/month-wise-receipt-payment-report', 'FinancialReportsController@monthWiseReceiptsAndPayments');

    //Fund Requisition
    Route::group(['prefix' => 'fund-requisition'], function () {
        Route::get('/purposes', [PurposeController::class, 'index']);
        Route::get('/purposes/search', [PurposeController::class, 'search']);
        Route::post('/purposes', [PurposeController::class, 'store']);
        Route::get('/purposes/{id}/edit', [PurposeController::class, 'edit']);
        Route::put('/purposes/{purpose}', [PurposeController::class, 'update']);
        Route::delete('/purposes/{purpose}/delete', [PurposeController::class, 'destroy']);

        // Report
        Route::get('reports', 'FundRequisitionReportController@index');
        Route::get('reports/{id}', 'FundRequisitionReportController@view');

        // Account Approval
        Route::get('account-approval', 'AccountApprovalController@index');
        Route::get('account-approval/create', 'AccountApprovalController@create');
        Route::post('account-approval', 'AccountApprovalController@store');
        Route::get('account-approval/{id}', 'AccountApprovalController@view');
        Route::get('account-approval/print/{id}', 'AccountApprovalController@print');

        // Audit Approval
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

    // Receive Banks
    Route::resource('/receive-banks', 'ReceiveBankController');

    // Receive Cheques
    Route::resource('/receive-cheques', 'ReceiveChequeController');

    Route::group(['prefix' => 'bill'], function () {

        Route::group(['prefix' => 'purchases'], function () {
            Route::get('/{any?}', function () {
                return view('basic-finance::forms.bill.bill_purchase');
            })->where('any', '.*');
        });
    });

    // APIs
    Route::group(['prefix' => '/api/v1'], function () {
        // accounts related
        Route::group(['prefix' => '/accounts'], function () {
            Route::get('/fetch-data', [AccountsAPIController::class, 'fetchData']);
            Route::get('/fetch-trial-balance-data', [TrialBalanceAccountsAPIController::class, 'fetchData']);
            Route::get('/fetch-account-code/{typeId}', [AccountCodeApiController::class, '__invoke']);
        });
        Route::get('/fetch-factories', [CompanyApiController::class, 'fetchFactories']);
        Route::get('/fetch-company-wise-all-current-asset-accounts/{companyId}', [CompanyWiseAccountsApiController::class, 'allAccounts']);
        Route::get('/fetch-company-wise-cash-accounts/{companyId}', [CompanyWiseAccountsApiController::class, 'cashAccounts']);
        Route::get('/fetch-company-wise-bank-accounts/{companyId}', [CompanyWiseAccountsApiController::class, 'bankAccounts']);
        Route::get('/fetch-company-wise-projects/{companyId}', [CompanyWiseProjectApiController::class, 'getProjects']);
        Route::get('/fetch-company-wise-all-projects/{companyId}', [CompanyWiseProjectApiController::class, 'getAllProjects']);
        Route::get('/fetch-project-wise-all-units/{projectId}', [UnitAPIController::class, 'getAllUnits']);
        Route::get('/fetch-project-wise-users/{companyId}/{projectId}', [ProjectWiseUsersApiController::class, 'getProjectWiseUsers']);
        Route::get('/fetch-project-wise-units/{companyId}/{projectId}', [ProjectWiseUnitApiController::class, '__invoke']);
        Route::get('/realization/fetch-loan-distribution-accounts', [LoanDistributionAccountsAPIController::class, '__invoke']);
        Route::get('/realization/fetch-distribution-accounts', [DistributionAccountsAPIController::class, '__invoke']);
        Route::get('/realization/fetch-foreign-bank-charge-accounts', [ForeignBankChargeAccountsAPIController::class, '__invoke']);
        Route::get('/realization/fetch-deduction-accounts', [DeductionAccountsAPIController::class, '__invoke']);
        Route::get('/get-banks', [BanksApiController::class, '__invoke']);
        Route::get('/get-bank-accounts/{bankId}', [BankAccountsApiController::class, '__invoke']);
        Route::get('/get-bank-accounts-with-chart-of-acc-id/{bankId}', [BankAccountsApiController::class, 'getBankAccounts']);
        Route::get('/get-cheque-no/{bankAccountId}', [ChequeNoApiController::class, '__invoke']);
        Route::get('/get-voucher-no', [VouchersController::class, 'voucherNo']);
        Route::get('/update-cheque-details/{chequeId}/{to}/{amount}/{trnDate}/{dueDate}', [ChequeDetailsUpdateApiController::class, '__invoke']);
        Route::get('/get-parent-bank-acc-no/{bankAccountId}', [ParentBankDebitAccApiController::class, '__invoke']);
        Route::get('/get-pay-mode-wise-accounts/{payMode}', [PayModeWiseAccounts::class, '__invoke']);

        Route::get("/get-bf-vouchers-by-bill-no", [CommonApiController::class, 'getBFVouchersByBillNo']);
        Route::get("/get-projects", [CommonApiController::class, 'fetchProjects']);
        Route::get("/get-units/{id}", [CommonApiController::class, 'fetchUnits']);
        Route::get("/requisition-no", [CommonApiController::class, 'getRequisitionNo']);
        Route::get('/get-purposes', [PurposesApiController::class, 'getPurposes']);
        Route::get('/get-receive-banks', [ReceiveBanksApiController::class, 'getReceiveBanks']);
        Route::get('/get-departments', [DepartmentsApiController::class, '__invoke']);
        Route::get('/get-item-types', [ItemTypesApiController::class, '__invoke']);
        Route::get('/get-item-categories/{type}', [ItemCategoriesApiController::class, '__invoke']);
        Route::get('/get-items/{type}/{itemCategoryId}', [ItemsApiController::class, '__invoke']);
        Route::get('/get-item-groups', [ItemsApiController::class, 'get_item_groups']);
        Route::get('/get-created-by-name', [CreatedByNameApiController::class, '__invoke']);
        Route::get('/get-account-types', [AccountTypeApiController::class, '__invoke']);
        Route::get('/get-account-type-short-form/{accountTypeId}', [AccountTypeShortFormApiController::class, '__invoke']);
        Route::get('/get-type-wise-parent-account/{typeId}', [TypeWiseParentAccountApiController::class, '__invoke']);

        Route::get('/get-type-wise-accounts', [TypeWiseAccountsApiController::class, '__invoke']);
        Route::get('/get-cash-in-hand-accounts', [CashInHadAccountsApiController::class, '__invoke']);

        Route::get('fetch-buyers-styles', [RealizationReportController::class, 'buyerStyleNames']);
        Route::get("fetch-buyers-po", [RealizationReportController::class, 'fetchPo']);
    });

    // Routes for unit
    Route::group(['prefix' => '/ratio-report'], function () {
        Route::get('/current-ratio', 'RatioController@currentRatio');
        Route::get('/quick-ratio', 'RatioController@quickRatio');
        Route::get('/working-capital-ratio', 'RatioController@workingCapitalRatio');
        Route::get('/debt-to-equity-ratio', 'RatioController@debtToEquityRatio');
        Route::get('/equity-ratio', 'RatioController@equityRatio');
        Route::get('/debt-ratio', 'RatioController@debtRatio');
        Route::get('/account-receivable-turnover-ratio', 'RatioController@accountRecivableTurnoverRatio');
        Route::get('/days-sales-outstanding-ratio', 'RatioController@daysSalesRatio');
        Route::get('/asset-turnover-ratio', 'RatioController@assetTurnoverRatio');
        Route::get('/inventory-turnover-ratio', 'RatioController@inventoryTurnoverRatio');
        Route::get('/days-sales-in-inventory-ratio', 'RatioController@daysSalesInInventoryRatio');
        Route::get('/accounts-payable-turnover-ratio', 'RatioController@accountsPayableTurnoverRatio');
        Route::get('/gross-profit-ratio', 'RatioController@grossProfitRatio');
        Route::get('/net-profit-ratio', 'RatioController@netProfitRatio');
        Route::get('/return-on-assets-ratio', 'RatioController@returnOnAssetRatio');
        Route::get('/return-on-capital-employeed-ratio', 'RatioController@returnOnCapitalEmployeedRatio');
        Route::get('/return-on-equity-ratio', 'RatioController@returnOnEquityRatio');
        Route::get('/earning-per-share-ratio', 'RatioController@earningPerShareRatio');
        Route::get('/price-earnings-ratio', 'RatioController@priceEarningsRatio');
        Route::get('/fixed-charge-coverage-ratio', 'RatioController@fixedChargeCoverageRatio');
        Route::get('/debt-service-coverage-ratio', 'RatioController@debtServiceCoverageRatio');
    });

    Route::post('/account-types', 'AccountTypeController@store');

    //Routes for Cash Management
    Route::group(['prefix' => '/cash-management'], function () {
        Route::get('/cash-book', 'FinancialReportsController@cashBook');
        Route::get('/detailed-cash-book', 'FinancialReportsController@detailedCashBook');
        Route::get('/project-wise-cash-books', [ProjectWiseCashBookReportController::class, 'index']);
        Route::get('/get-report-data', [ProjectWiseCashBookReportController::class, 'getReportData']);
        Route::get('/get-pdf', [ProjectWiseCashBookReportController::class, 'getPdf']);
        Route::get('/get-excel', [ProjectWiseCashBookReportController::class, 'getExcel']);
    });

    //Routes for Bank Management
    Route::group(['prefix' => '/bank-management'], function () {
        Route::get('/bank-book', 'FinancialReportsController@bankBook');
        Route::get('/detailed-bank-book', 'FinancialReportsController@detailedBankBook');
    });

    // Accounting Realization
    Route::group(['prefix' => '/accounting-realization'], function () {
        Route::get('/', [AccountingRealiationController::class, 'index']);
        Route::get('/create', [AccountingRealiationController::class, 'create']);
        Route::get('/edit', [AccountingRealiationController::class, 'edit']);
        Route::get('/{accountRealization}/fetch', [AccountingRealiationController::class, 'fetch']);
        Route::post('/store', [AccountingRealiationController::class, 'store']);
        Route::put('/{accountRealization}', [AccountingRealiationController::class, 'update']);
        Route::delete('/{accountRealization}', [AccountingRealiationController::class, 'destroy']);

        Route::get('/mis-report', [RealizationReportController::class, 'misReport']);
        Route::get('/mis-report-excel', [RealizationReportController::class, 'misReportExcel']);

        Route::group(['prefix' => 'vouchers'], function () {
            Route::get('/create/{accountRealization}', [RealizationToVoucherController::class, 'create']);
            Route::get('/fetch-realization-data/{accountRealization}', [RealizationToVoucherController::class, 'getRealizationData']);
        });
    });
});
