<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\Reports\TrimsStore;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\Exception;
use SkylarkSoft\GoRMG\Inventory\Exports\TrimsStore\MonthlyStockUpReportExport;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsStore\Reports\MonthlyStockUpReportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TrimsStoreMonthlyStockUpReportController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('inventory::trims-store.reports.monthly_stock_up_report.index');
    }

    /**
     * @param Request $request
     * @param MonthlyStockUpReportService $service
     * @return Application|Factory|View
     */
    public function getReport(Request $request, MonthlyStockUpReportService $service)
    {
        $reportData = $service->generateReport($request);

        return view('inventory::trims-store.reports.monthly_stock_up_report.body', [
            'reportData' => $reportData,
        ]);
    }

    /**
     * @param Request $request
     * @param MonthlyStockUpReportService $service
     * @return mixed
     */
    public function pdf(Request $request, MonthlyStockUpReportService $service)
    {
        $reportData = $service->generateReport($request);

        $pdf = PDF::loadView('inventory::trims-store.reports.monthly_stock_up_report.pdf', [
            'reportData' => $reportData,
        ])->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('monthly_stock_up_report.pdf');
    }

    /**
     * @param Request $request
     * @param MonthlyStockUpReportService $service
     * @return BinaryFileResponse
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function excel(Request $request, MonthlyStockUpReportService $service): BinaryFileResponse
    {
        $reportData = $service->generateReport($request);

        return Excel::download(new MonthlyStockUpReportExport([
            'reportData' => $reportData,
        ]), 'monthly_stock_up_report.xlsx');
    }
}
