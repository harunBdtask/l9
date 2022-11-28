<?php

namespace SkylarkSoft\GoRMG\Misdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\Exception;
use SkylarkSoft\GoRMG\Misdroplets\Exports\CutToFinishExport;
use SkylarkSoft\GoRMG\Misdroplets\Services\CutToFinishReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CutToFinishReportController extends Controller
{
    public function index()
    {
        $buyers = Buyer::get(['id', 'name']);
        return view('misdroplets::reports.cut_to_finish_report', ['buyers' => $buyers]);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function generate(Request $request)
    {
        $buyerId = $request->get('buyer_id');
        $orderId = $request->get('order_id');
        $startDate = $request->get('start_date', date('Y-m-d'));
        $endDate = $request->get('end_date', date('Y-m-d'));

        $reportData = CutToFinishReportService::make($buyerId, $orderId, $startDate, $endDate)->report();

        return view('misdroplets::reports.tables.cut_to_finish_report_table', [
            'reportData' => $reportData,
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function generatePdf(Request $request)
    {
        $buyerId = $request->get('buyer_id');
        $orderId = $request->get('order_id');
        $startDate = $request->get('start_date', date('Y-m-d'));
        $endDate = $request->get('end_date', date('Y-m-d'));

        $reportData = CutToFinishReportService::make($buyerId, $orderId, $startDate, $endDate)->report();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView(
                'misdroplets::reports.downloads.pdf.cut_to_finish_report_pdf',
                compact('reportData')
            )->setPaper('a4')->setOrientation('landscape');

        return $pdf->stream('cut-to-finish-report.pdf');
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generateExcel(Request $request): BinaryFileResponse
    {
        $buyerId = $request->get('buyer_id');
        $orderId = $request->get('order_id');
        $startDate = $request->get('start_date', date('Y-m-d'));
        $endDate = $request->get('end_date', date('Y-m-d'));

        $reportData = CutToFinishReportService::make($buyerId, $orderId, $startDate, $endDate)->report();

        return Excel::download(new CutToFinishExport($reportData), 'cut-to-finish-report.xlsx');

    }
}
