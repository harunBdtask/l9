<?php


namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Commercial\Exports\PITrackingReportExcel;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Commercial\Services\Report\PITrackingReport\PITrackingReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PITrackingReportController extends Controller
{
    public function index()
    {
        $buyers = Buyer::query()->get(['id', 'name']);
        $piNos = ProformaInvoice::query()
            ->where('pi_basis', 1)
            ->latest()
            ->pluck('pi_no');
        return view('commercial::reports.pi-tracking-report.index', compact('buyers', 'piNos'));
    }

    public function getReport(Request $request)
    {
        $reportData = (new PITrackingReportService($request))->getReport();
        $buyerName = Buyer::query()->where('id', $request->get('buyer_id'))->first()->name ?? '';
        return view('commercial::reports.pi-tracking-report.table', compact('reportData', 'buyerName'));
    }

    public function getReportPdf(Request $request)
    {
        $reportData = (new PITrackingReportService($request))->getReport();
        $buyerName = Buyer::query()->where('id', $request->get('buyer_id'))->first()->name ?? '';

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('commercial::reports.pi-tracking-report.pdf', compact('reportData', 'buyerName'))
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('pi_tracking_report.pdf');
    }

    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $reportData = (new PITrackingReportService($request))->getReport();
        $buyerName = Buyer::query()->where('id', $request->get('buyer_id'))->first()->name ?? '';
        return Excel::download(new PITrackingReportExcel($reportData, $buyerName), 'pi_tracking_report.xlsx');
    }
}
