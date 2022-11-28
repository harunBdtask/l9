<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use SkylarkSoft\GoRMG\Merchandising\Exports\BuyerSeasonOrderListExport;
use SkylarkSoft\GoRMG\Merchandising\Services\BuyerSeasonColorOrderReportService;

class BuyerSeasonColorOrderReportController extends Controller
{
    public function index()
    {
        $buyers = Buyer::query()->get(['name', 'id']);

        return view("merchandising::reports.buyer_season_color_order_report", compact('buyers'));
    }

    public function getReport(Request $request)
    {
        $reportData = (new BuyerSeasonColorOrderReportService($request))->report();

        return view('merchandising::reports.includes.buyer_season_color_order_table', $reportData, ['type' => null]);
    }

    public function getReportPdf(Request $request): Response
    {
        $reportData = (new BuyerSeasonColorOrderReportService($request))->report();
        $signature = ReportSignatureService::getSignatures("BUYER SEASON COLOR ORDER IMAGE REPORT", $reportData['buyer_id']);
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::reports.downloads.pdf.buyer_season_color_order_report_pdf', $reportData,
                ['type' => null], ['signature' => $signature]
            )
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);

        return $pdf->download($reportData['buyer'] . '_po_list.pdf');
    }

    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $reportData = (new BuyerSeasonColorOrderReportService($request))->report();
        $type = "color";

        return Excel::download(new BuyerSeasonOrderListExport($reportData, $type), $reportData['buyer'] . '_po_list.xlsx');
    }

    public function getReportPrint(Request $request)
    {
        $reportData = (new BuyerSeasonColorOrderReportService($request))->report();
        $signature = ReportSignatureService::getSignatures("BUYER SEASON COLOR ORDER IMAGE REPORT", $reportData['buyer_id']);
        return view('merchandising::reports.buyer_season_color_order_report_print', $reportData, ['type' => null, 'signature' => $signature]);
    }
}
