<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\Exception;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\BuyerStyleWiseCuttingReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\BuyerStyleWiseCuttingReportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BuyerStyleWiseCuttingReportController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('cuttingdroplets::reports.buyer_style_wise_cutting_report');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getReport(Request $request)
    {
        $reportData = (new BuyerStyleWiseCuttingReportService($request))->report();
        return view('cuttingdroplets::reports.includes.buyer_style_wise_cutting_report_include', $reportData);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getReportPdf(Request $request)
    {
        $reportData = (new BuyerStyleWiseCuttingReportService($request))->report();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('cuttingdroplets::reports.downloads.pdf.buyer_style_wise_cutting_report_pdf', $reportData)
            ->setPaper('a3')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer')
            ]);
        return $pdf->stream('buyer_style_wise_cutting_report.pdf');
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $reportData = (new BuyerStyleWiseCuttingReportService($request))->report();

        return Excel::download(new BuyerStyleWiseCuttingReportExport($reportData), 'buyer_style_wise_cutting_report.xlsx');
    }
}
