<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Misdroplets\Controllers\AuditController;
use SkylarkSoft\GoRMG\Misdroplets\Controllers\ColorWiseProductionSummaryReport;
use SkylarkSoft\GoRMG\Misdroplets\Controllers\CutToFinishReportController;
use SkylarkSoft\GoRMG\Misdroplets\Controllers\MisReportController;
use SkylarkSoft\GoRMG\Misdroplets\Controllers\MonthlyEfficiencySummaryReportController;

Route::middleware(['web', 'auth', 'menu-auth'])->group(function () {

    Route::match(['GET', 'POST'], '/factory-wise-cutting-report', [MisReportController::class, 'factoryWiseCuttingReport']);
    Route::match(['GET', 'POST'], '/factory-wise-print-sent-received-report', [MisReportController::class, 'factoryWisePrintSentReceivedReport']);
    Route::match(['GET', 'POST'], '/factory-wise-input-output-report', [MisReportController::class, 'factoryWiseInputOutputReport']);

    Route::match(['GET', 'POST'], '/color-wise-production-summary-report', [ColorWiseProductionSummaryReport::class, 'colorWiseProductionSummaryReport']);
    Route::get('/color-wise-production-summary-report-download/{type}/{from_date}/{to_date}/{current_page}', [ColorWiseProductionSummaryReport::class, 'colorWiseProductionSummaryReportDownload']);

    Route::get('/monthly-efficiency-summary-report', [MonthlyEfficiencySummaryReportController::class, 'getMonthlyEfficiencySummaryReport']);
    Route::get('/monthly-efficiency-summary-report-download/{type}/{year}/{month}', [MonthlyEfficiencySummaryReportController::class, 'getMonthlyEfficiencySummaryReportDownload']);

    Route::get('/audit-report', [AuditController::class, 'auditReport']);
    Route::get('audit-report-download/{type}', [AuditController::class, 'auditReportDownload']);

    Route::prefix('cut-to-finish-report')->group(function () {
        Route::get('/', [CutToFinishReportController::class, 'index']);
        Route::post('/generate', [CutToFinishReportController::class, 'generate']);
        Route::get('/generate/pdf', [CutToFinishReportController::class, 'generatePdf']);
        Route::get('/generate/xls', [CutToFinishReportController::class, 'generateExcel']);
    });
});
