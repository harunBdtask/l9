<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Support\Carbon;
use PDF;
use SkylarkSoft\GoRMG\DyesStore\DTOs\StockSummaryDTO;
use SkylarkSoft\GoRMG\DyesStore\Exports\DailyStockSummaryReportExport;
use SkylarkSoft\GoRMG\DyesStore\Services\Reports\DyesAndChemicalsReportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DyesAndChemicalsStockSummaryReportController extends Controller
{
    public function reportView()
    {
        $firstDate = request('first_date') ?? Carbon::now()->startOfMonth()->toDateString();
        $lastDate = request('last_date') ?? Carbon::now()->endOfMonth()->toDateString();
        $storeId = request('store_id');

        $items = DyesAndChemicalsReportService::data();

        return view('dyes-store::report.dyes_chemicals.stock_summary_report', [
            'first_date' => $firstDate,
            'last_date' => $lastDate,
            'storeId' => $storeId,
            'items' => $items,
            'type' => 'dyesChemicalsReceive'
        ]);
    }

    public function dailyReport()
    {
        $date = date('Y-m-d');
        $reportData = (new StockSummaryDTO())->setDate($date)->reportData();
        return view('dyes-store::report.dyes_stock_summary.report', ['report' => $reportData]);
    }

    public function dailyReportPdf()
    {
        $date = date('Y-m-d');
        $reportData = (new StockSummaryDTO())->setDate($date)->reportData();
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('dyes-store::report.dyes_stock_summary.pdf', ['report' => $reportData])
            ->setPaper('a4')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer')
            ]);
        return $pdf->stream('dyes_store_stock_summary_report' . '.pdf');
    }

    public function dailyReportExcel(): BinaryFileResponse
    {
        $date = date('Y-m-d');
        $reportData = (new StockSummaryDTO())->setDate($date)->reportData();
        return Excel::download(new DailyStockSummaryReportExport($reportData), 'dyes_store_stock_summary_report.xlsx');
    }
}
