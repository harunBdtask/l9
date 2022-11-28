<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\MonthlyCuttingInputReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\CuttingSummaryReport\MonthlyCuttingInputSummaryReport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MonthlyCuttingInputReportController extends Controller
{
    public function index()
    {
        return view('cuttingdroplets::reports.monthly_cutting_input_report');
    }

    public function getReport(Request $request)
    {
        $reportData = (new MonthlyCuttingInputSummaryReport())->init($request->get('month'))->generate();
//        dd($reportData);
        return view('cuttingdroplets::reports.tables.monthly_cutting_input_report_table', $reportData);
    }

    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $reportData = (new MonthlyCuttingInputSummaryReport())->init($request->get('month'))->generate();
        return Excel::download(new MonthlyCuttingInputReportExport($reportData), 'monthly_cutting_input_report.xlsx');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getReportPdf(Request $request)
    {
        $reportData = (new MonthlyCuttingInputSummaryReport())->init($request->get('month'))->generate();
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('cuttingdroplets::reports.downloads.pdf.monthly_cutting_input_report_pdf', $reportData)
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer')
            ]);
        return $pdf->stream('monthly_cutting_input_report.pdf');
    }
}
