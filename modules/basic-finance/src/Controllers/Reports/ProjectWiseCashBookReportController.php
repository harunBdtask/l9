<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\Reports;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\BasicFinance\Exports\ProjectWiseCashBookReportExport;
use SkylarkSoft\GoRMG\BasicFinance\Services\Reports\ProjectWiseCashReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProjectWiseCashBookReportController extends Controller
{
    public function index()
    {
        $factories = Factory::query()->pluck('factory_name', 'id');
        $fromDate = Carbon::now()->format('Y-m-d');
        $toDate = Carbon::now()->format('Y-m-d');

        return view('basic-finance::reports.cash-management.project-wise-cash-book.index', [
            'factories' => $factories,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    public function getReportData(Request $request, ProjectWiseCashReportService $service)
    {
        $reportData = $service->getReportData($request);

        return view('basic-finance::reports.cash-management.project-wise-cash-book.table', $reportData);
    }

    public function getPdf(Request $request, ProjectWiseCashReportService $service)
    {
        $reportData = $service->getReportData($request);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('basic-finance::reports.cash-management.project-wise-cash-book.pdf', $reportData)
            ->setPaper('a4')
            ->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('project_wise_cash_book_report.pdf');
    }

    public function getExcel(Request $request, ProjectWiseCashReportService $service): BinaryFileResponse
    {
        $reportData = $service->getReportData($request);

        return Excel::download(
            new ProjectWiseCashBookReportExport($reportData),
            'project_wise_cash_book_report.xlsx'
        );
    }
}
