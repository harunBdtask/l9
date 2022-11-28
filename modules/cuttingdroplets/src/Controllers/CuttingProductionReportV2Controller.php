<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\CuttingProductionReportV2Export;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\CuttingProductionReportV2Service;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CuttingProductionReportV2Controller extends Controller
{
    public function index()
    {
        $buyers = Buyer::query()->get(['id', 'name']);
        return view('cuttingdroplets::reports.cutting_production_report_v2', compact('buyers'));
    }

    public function getReport(Request $request)
    {
        $reportData = (new CuttingProductionReportV2Service($request->get('buyer_id')))->generateReport();
        return view('cuttingdroplets::reports.tables.cutting_production_report_v2_table', compact('reportData'));
    }

    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $reportData = (new CuttingProductionReportV2Service($request->get('buyer_id')))->generateReport();
        return Excel::download(new CuttingProductionReportV2Export($reportData), 'cutting_production_report_v2.xlsx');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getReportPdf(Request $request)
    {
        $reportData = (new CuttingProductionReportV2Service($request->get('buyer_id')))->generateReport();
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('cuttingdroplets::reports.downloads.pdf.cutting_production_report_v2_pdf', compact('reportData'))
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer')
            ]);
        return $pdf->stream('cutting_production_report_v2.pdf');
    }
}
