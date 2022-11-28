<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\Reports;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Inventory\Exports\FinishFabricReceiveReportExport;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\ReportService\DailyFinishFabricReceiveMailReportService;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\ReportService\FinishFabricReceiveReportService;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;

class FinishFabricReceiveReportController extends Controller
{
    public function finishFabricReceiveReport(Request $request)
    {
        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        $colors = Color::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        return view('inventory::reports.finish_fabric_receive_report.index', [
            'buyers' => $buyers,
            'colors' => $colors,
        ]);
    }

    public function dateWiseFinishFabricReceiveReport(Request $request, FinishFabricReceiveReportService $reportService)
    {
        $reportData = $reportService->getReportData($request);
        return view('inventory::reports.finish_fabric_receive_report.table', [
            'fabricReceives' => $reportData,
        ]);
    }

    public function pdf(Request $request, FinishFabricReceiveReportService $reportService)
    {
        $reportData = $reportService->getReportData($request);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('inventory::reports.finish_fabric_receive_report.pdf', [
                'fabricReceives' => $reportData,
            ])
            ->setPaper('a4')
            ->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('finish_fabric_receive_report.pdf');
    }

    public function excel(Request $request, FinishFabricReceiveReportService $reportService)
    {
        $reportData = $reportService->getReportData($request);
        return Excel::download(new FinishFabricReceiveReportExport($reportData), 'finish_fabric_receive_report.xlsx');
    }
}
