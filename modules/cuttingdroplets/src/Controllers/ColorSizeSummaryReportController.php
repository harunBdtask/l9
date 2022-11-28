<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\ColorSizeSummaryReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\ColorSizeSummaryReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\OrderService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ColorSizeSummaryReportController extends Controller
{
    public function index()
    {
        $buyers = Buyer::query()->pluck('name', 'id')->all();
        $weeks = ColorSizeSummaryReportService::getWeeks();
        $cutOffs = (new OrderService())->cutOffForSelect();

        return view('cuttingdroplets::reports.color_size_summary_report',
            compact('buyers', 'weeks', 'cutOffs')
        );
    }

    public function getReport(Request $request)
    {
        $reportData = ColorSizeSummaryReportService::report($request);
        return view('cuttingdroplets::reports.tables.color_size_summary_report_table', $reportData);
    }

    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $reportData = ColorSizeSummaryReportService::report($request);
        return Excel::download(new ColorSizeSummaryReportExport($reportData), 'color-size-summary-report.xlsx');
    }
}
