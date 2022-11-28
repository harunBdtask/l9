<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\Printembrdroplets\Exports\DailyPrintEmbroideryReportExport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateFloorWisePrintEmbrReport;
use PDF;
use SkylarkSoft\GoRMG\Printembrdroplets\Services\DailyPrintEmbrReportService;

class DailyPrintEmbrReportController extends Controller
{
    public function getReport(Request $request)
    {
        $date = $request->date ?? Date('Y-m-d');

        $reportData = (new DailyPrintEmbrReportService())->generateReport($date);

        return view('printembrdroplets::reports.daily_print_embr_report_iris_fabrics', [
            'date' => $date,
            'reportData' => $reportData
        ]);
    }

    public function downloadReport(Request $request)
    {
        $type = $request->type ?? null;
        $date = $request->date ?? Date('Y-m-d');

        $reportData = (new DailyPrintEmbrReportService())->generateReport($date);
        if ($type == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('printembrdroplets::reports.downloads.pdf.daily_print_embr_report_iris_fabrics_pdf', compact('reportData'))
                ->setPaper('a4')->setOrientation('landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer')
                ]);
            return $pdf->stream('daily-print-embr-report-' . date("d-m-Y", strtotime($date)) . '.pdf');
        } else {
            return Excel::download(new DailyPrintEmbroideryReportExport($reportData), 'daily-print-embr-report-report-' . date("d-m-Y", strtotime($date)) . '.xlsx');
        }
    }
}
