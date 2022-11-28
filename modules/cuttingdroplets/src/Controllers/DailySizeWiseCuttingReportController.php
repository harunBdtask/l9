<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\DailySizeWiseCuttingReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\DailySizeWiseCuttingReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DailySizeWiseCuttingReportController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $cuttingFloors = CuttingFloor::query()->get();
        return view('cuttingdroplets::reports.daily_size_wise_cutting_report', [
            'cuttingFloors' => $cuttingFloors
        ]);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function getReport(Request $request)
    {
        $reportData = (new DailySizeWiseCuttingReportService($request->get('date'), $request->get('floor_id')))->report();
        return view('cuttingdroplets::reports.includes.daily_size_wise_cutting_report_include', $reportData);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getReportPdf(Request $request)
    {
        $reportData = (new DailySizeWiseCuttingReportService($request->get('date'), $request->get('floor_id')))->report();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('cuttingdroplets::reports.downloads.pdf.daily_size_wise_cutting_report_pdf', $reportData)
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer')
            ]);
        return $pdf->stream('daily_size_wise_cutting_report.pdf');
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $reportData = (new DailySizeWiseCuttingReportService($request->get('date'), $request->get('floor_id')))->report();

        return Excel::download(new DailySizeWiseCuttingReportExport($reportData), 'daily_size_wise_cutting_report.xlsx');
    }
}
