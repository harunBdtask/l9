<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\Reports\TrimsStore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Inventory\Exports\TrimsStore\DailyDetailsReportExport;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsStore\Reports\DailyDetailsReportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TrimsStoreDailyDetailsReportController extends Controller
{
    public function index()
    {
        return view('inventory::trims-store.reports.daily-details-report.index');
    }

    public function getReport(Request $request)
    {
        $reportData = (new DailyDetailsReportService($request))->generate();
        return view('inventory::trims-store.reports.daily-details-report.table', compact('reportData'));
    }

    public function pdf(Request $request)
    {
        $reportData = (new DailyDetailsReportService($request))->generate();

        $pdf = PDF::loadView('inventory::trims-store.reports.daily-details-report.pdf', compact('reportData'))
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('daily_details_report.pdf');
    }

    public function excel(Request $request): BinaryFileResponse
    {
        $reportData = (new DailyDetailsReportService($request))->generate();
        return Excel::download(new DailyDetailsReportExport($reportData), 'daily_details_report.xlsx');
    }
}
