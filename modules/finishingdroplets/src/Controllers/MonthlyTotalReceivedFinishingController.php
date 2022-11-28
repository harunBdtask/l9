<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\MonthlyTotalReceivedFinishingExport;
use SkylarkSoft\GoRMG\Finishingdroplets\Services\MonthlyTotalReceivedFinishingReportService;

class MonthlyTotalReceivedFinishingController extends Controller
{
    public function monthlyTotalReceivedFinishingReport(Request $request)
    {
        return view('finishingdroplets::reports.monthly_total_received_finishing');
    }

    public function getMonthlyTotalReceivedFinishing(Request $request)
    {
        //dd($request->get('month'));
        $reportData = (new MonthlyTotalReceivedFinishingReportService())->init($request->get('month'))->generate();

        return view('finishingdroplets::reports.tables.monthly_total_received_finishing_report_table', $reportData);
    }

    public function getReportPdf(Request $request)
    {
        $reportData = (new MonthlyTotalReceivedFinishingReportService())->init($request->get('month'))->generate();
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('finishingdroplets::reports.downloads.pdf.monthly_total_received_finishing_download', $reportData)
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('monthly_total_received_finishing.pdf');
    }

    public function getReportExcel(Request $request)
    {
        $reportData = (new MonthlyTotalReceivedFinishingReportService())->init($request->get('month'))->generate();

        return Excel::download(new MonthlyTotalReceivedFinishingExport($reportData), 'monthly_total_receive_finishing.xlsx');
    }
}
