<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\Reports;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Inventory\Exports\FinishFabricIssueReportExport;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\ReportService\FinishFabricIssueReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;


class FinishFabricIssueReportController extends Controller
{
    public function finishFabricIssueReport()
    {
        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        $colors = Color::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        return view('inventory::reports.finish_fabric_issue_report.index', [
            'buyers' => $buyers,
            'colors' => $colors,
        ]);
    }

    public function dateWiseFinishFabricIssueReport(Request $request,FinishFabricIssueReportService $reportService)
    {
        $reportData = $reportService->getReportData($request);
        return view('inventory::reports.finish_fabric_issue_report.table',[
            'fabricIssues' => $reportData
        ]);
    }

    public function pdf(Request $request,FinishFabricIssueReportService $reportService)
    {
        $reportData = $reportService->getReportData($request);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('inventory::reports.finish_fabric_issue_report.pdf', [
                'fabricIssues' => $reportData,
            ])->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('finish_fabric_issue_report.pdf');
    }

    public function excel(Request $request,FinishFabricIssueReportService $reportService)
    {
        $reportData = $reportService->getReportData($request);

        return Excel::download(new FinishFabricIssueReportExport($reportData), 'finish_fabric_issue_report.xlsx');
    }
}
