<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\MonthlyCuttingInputReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\YearlySummaryReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\CuttingSummaryReport\YearlyCuttingInputSummaryReport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class YearlySummaryReportController extends Controller
{
    public function index()
    {
        return view('cuttingdroplets::reports.yearly_summary_report');
    }

    public function getReport(Request $request)
    {
        $reportData = (new YearlyCuttingInputSummaryReport())->init($request->get('year'))->generate();
        return view('cuttingdroplets::reports.tables.yearly_summary_report_table', $reportData);
    }

    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $reportData = (new YearlyCuttingInputSummaryReport())->init($request->get('year'))->generate();
        return Excel::download(new YearlySummaryReportExport($reportData), 'yearly_summary_report.xlsx');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getReportPdf(Request $request)
    {
        $reportData = (new YearlyCuttingInputSummaryReport())->init($request->get('year'))->generate();
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('cuttingdroplets::reports.downloads.pdf.yearly_summary_report_pdf', $reportData)
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer')
            ]);
        return $pdf->stream('yearly_summary_report.pdf');
    }
}
