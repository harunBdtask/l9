<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\DailyCuttingBalanceReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\DailyCuttingBalanceReportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DailyCuttingBalanceReportController extends Controller
{
    public function index()
    {
        (new DailyCuttingBalanceReportService())->resetTodaysDdata();
        $cutting_report = (new DailyCuttingBalanceReportService())->report();
        return view('cuttingdroplets::reports.daily_cutting_balance_report', compact('cutting_report'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function pdf(Request $request)
    {
        $cutting_report = (new DailyCuttingBalanceReportService())->report();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('cuttingdroplets::reports.downloads.pdf.daily_cutting_balance_report_pdf', compact('cutting_report'))
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer')
            ]);
        return $pdf->stream('daily_cutting_balance_report.pdf');
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function excel(Request $request): BinaryFileResponse
    {
        $reportData = (new DailyCuttingBalanceReportService())->report();

        return Excel::download(new DailyCuttingBalanceReportExport($reportData), 'daily_cutting_balance_report.xlsx');
    }
}
