<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Exports\BuyerWiseWOReportExport;
use SkylarkSoft\GoRMG\Merchandising\Services\BudgetWiseWOReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BudgetWiseWOReportController extends Controller
{
    public function index()
    {
        $companies = Factory::query()->get(['id', 'factory_name']);
        return view('merchandising::reports.budget_wise_workorder_report', compact('companies'));
    }

    public function getReport(Request $request)
    {
        $reportData = (new BudgetWiseWOReportService($request))->report();
        return view('merchandising::reports.includes.budget_wise_workorder_report_table', $reportData);
    }

    public function getReportPdf(Request $request)
    {
        $reportData = (new BudgetWiseWOReportService($request))->report();
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::reports.downloads.pdf.budget_wise_workorder_report_pdf', $reportData)
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('order_volume_report.pdf');
    }

    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $reportData = (new BudgetWiseWOReportService($request))->report();
        return Excel::download(new BuyerWiseWOReportExport($reportData), 'buyer_Wise_wo_report.xlsx');
    }
}
