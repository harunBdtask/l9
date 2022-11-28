<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Exports\ColorWiseOrderVolumeReportExport;
use SkylarkSoft\GoRMG\Merchandising\Services\ColorWiseOderVolumeReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ColorWiseOrderVolumeReportController extends Controller
{
    public function index()
    {
        $buyers = Buyer::query()
            ->get();
        $seasons = Season::query()
            ->pluck('season_name', 'id')
            ->prepend('Select', '');
        return view('merchandising::reports.color_wise_order_volume_report', [
            'buyers' => $buyers,
            'seasons' => $seasons
        ]);
    }


    public function getSeason(Request $request): JsonResponse
    {
        $buyer_id = $request->buyer;

        $seasons = Season::query()
            ->where('buyer_id', $buyer_id)
            ->get();
        return response()->json($seasons);
    }

    public function getReport(Request $request)
    {
        $reportData = (new ColorWiseOderVolumeReportService($request))->report();
        return view('merchandising::reports.color_wise_order_volume_report_table', [
            'reportData' => $reportData
        ]);
    }

    public function getPdf(Request $request)
    {
        $reportData = (new ColorWiseOderVolumeReportService($request))->report();
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::reports.downloads.pdf.color_wise_order_volume_report',
                ['reportData' => $reportData])
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer',),
            ]);
        return $pdf->stream('color_wise_order_volume_report.pdf');
    }

    public function getExcel(Request $request): BinaryFileResponse
    {
        $reportData = (new ColorWiseOderVolumeReportService($request))->report();
        return Excel::download(new ColorWiseOrderVolumeReportExport(['reportData' => $reportData]), 'color_wise_order_volume_report.xlsx');
    }
}
