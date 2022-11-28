<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\Subcontract\Services\Reports\GrayFabricStockSummaryReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class GreyFabricStockSummaryReportController extends Controller
{
    public function index()
    {
        $parties = Buyer::query()->pluck('name', 'id')
            ->prepend('All', 'all')
            ->prepend('Select', 0);

        return view(PackageConst::VIEW_PATH . 'report.grey-fabric-stock-summary.index', [
            'parties' => $parties,
        ]);
    }

    public function getReportData(Request $request, GrayFabricStockSummaryReportService $service)
    {
        $reportData = $service->getReportData($request);

        return view(PackageConst::VIEW_PATH . 'report.grey-fabric-stock-summary.body', $reportData);
    }

    public function pdf(Request $request, GrayFabricStockSummaryReportService $service)
    {
        $reportData = $service->getReportData($request);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView(PackageConst::VIEW_PATH . 'report.grey-fabric-stock-summary.pdf', $reportData)
            ->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->download('grey_stock_summary_report.pdf');
    }

    public function excel(Request $request, GrayFabricStockSummaryReportService $service)
    {
        // TODO;
    }
}
