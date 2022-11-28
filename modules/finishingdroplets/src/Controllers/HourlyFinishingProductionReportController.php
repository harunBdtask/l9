<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\Exception;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\HourlyFinishingProductionReportExport;
use SkylarkSoft\GoRMG\Finishingdroplets\Services\HourlyFinishingProductionReportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class HourlyFinishingProductionReportController extends Controller
{
    public function index()
    {
        return view('finishingdroplets::reports.hourly_finishing_production_report');
    }

    public function dashboard()
    {
        return view('finishingdroplets::reports.hourly_finishing_production_dashboard');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function getReport(Request $request)
    {
        $reportService = new HourlyFinishingProductionReportService($request);
        $reportData = $reportService->report();
        $total = $reportService->getTotal();
        return view('finishingdroplets::reports.tables.hourly_finishing_production_table',
            compact('reportData', 'total'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getReportPdf(Request $request)
    {
        $reportService = new HourlyFinishingProductionReportService($request);
        $reportData = $reportService->report();
        $total = $reportService->getTotal();
        $reportHead = [
            'date' => Carbon::make($request->get('date'))->format('d-m-Y'),
        ];
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('finishingdroplets::reports.downloads.pdf.hourly_finishing_production_pdf',
                compact('reportData', 'total'),
                $reportData, $reportHead, [], ['format' => 'A4-L']
            )->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ])->setOrientation('landscape');

        return $pdf->stream($reportHead['date'] . '_hourly-finishing-production.pdf');
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $reportService = new HourlyFinishingProductionReportService($request);
        $reportData = $reportService->report();
        $total = $reportService->getTotal();
        return Excel::download(new HourlyFinishingProductionReportExport($reportData, $total), 'hourly-finishing-production.xlsx');
    }
}
