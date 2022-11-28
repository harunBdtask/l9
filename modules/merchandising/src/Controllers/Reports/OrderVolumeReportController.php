<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\Merchandising\Exports\OrderVolumeReportExport;
use SkylarkSoft\GoRMG\Merchandising\Services\OrderVolumeReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use PDF;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderVolumeReportController extends Controller
{
    public function index()
    {
        return view('merchandising::reports.order_volume_report');
    }

    public function getReport(Request $request)
    {
        $reportData = (new OrderVolumeReportService($request))->report();
        $type = "blade";
        return view('merchandising::reports.includes.order_volume_report_table', ['type' => $type], $reportData);
    }

    public function getReportData(Request $request): JsonResponse
    {
        $colorsChart = ["#FF8A65", "#FFB74D", "#81C784", "#4DB6AC", "#4FC3F7", "#5C6BC0", "#107884", "#FF7043", "#795548", "#CDDC39", "#607D8B", "#AB47BC"];

        $reportData = (new OrderVolumeReportService($request))->report();
        $reportData['colors'] = collect($reportData['reportData'])->map(function () use ($colorsChart) {
            return $colorsChart[array_rand($colorsChart, 1)];
        })->values();
        return response()->json($reportData);
    }

    public function getReportPdf(Request $request)
    {
        $reportData = (new OrderVolumeReportService($request))->report();
        $signature = ReportSignatureService::getSignatures("ORDER VOLUME REPORT");
        $pdf = PDF::loadView('merchandising::reports.downloads.pdf.order_volume_report_pdf',
            $reportData, ['signature' => $signature])
            ->setPaper('a4')->setOrientation('portrait')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);
        return $pdf->download('order_volume_report.pdf');
    }

    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $reportData = (new OrderVolumeReportService($request))->report();
        return Excel::download(new OrderVolumeReportExport($reportData), 'order_volume_report.xlsx');
    }
}
