<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\Exception;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\FinishingProductionReportV2Export;
use SkylarkSoft\GoRMG\Finishingdroplets\Services\FinishingProductionReportV2;
use SkylarkSoft\GoRMG\Finishingdroplets\Services\FinishingProductionReportV3;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinishingFloor;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FinishingProductionReportV2Controller extends Controller
{
    public function index()
    {
        $buyers = Buyer::query()
            ->select(['id', 'name'])
            ->get();
        $finishFloors = FinishingFloor::query()->get();

        return view('finishingdroplets::reports.finishing_production_report_v2', compact('buyers', 'finishFloors'));
    }

    public function getReport(Request $request)
    {
        $fromDate = $request->get('from_date', date('Y-m-d'));
        $toDate = $request->get('to_date', date('Y-m-d'));
        $buyer = $request->get('buyer_id');
        $floorId = $request->get('floor_id');
        $limit = $request->get('limit');
        $offset = $request->get('offset');

        $reportData = (new FinishingProductionReportV3($fromDate, $toDate, $buyer, $floorId))->generateReport('BLADE', $limit, $offset);
        // $reportData = (new FinishingProductionReportV2($date, $buyer))->generateReport();

        return view('finishingdroplets::reports.tables.finishing_production_report_v2_table', compact('reportData'));
    }

    public function getReportPdf(Request $request)
    {
//        $date = $request->get('date', date('Y-m-d'));
//        $buyer = $request->get('buyer_id', null);
//        $reportData = (new FinishingProductionReportV2($date, $buyer))->generateReport();

        $fromDate = $request->get('from_date', date('Y-m-d'));
        $toDate = $request->get('to_date', date('Y-m-d'));
        $buyer = $request->get('buyer_id');
        $floorId = $request->get('floor_id');
        $limit = 0;
        $offset = 0;

        $reportData = (new FinishingProductionReportV3($fromDate, $toDate, $buyer, $floorId))->generateReport('PDF', $limit, $offset);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('finishingdroplets::reports.downloads.pdf.finishing_production_report_v2_pdf', compact('reportData'))
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer')
            ]);
        return $pdf->stream('finishing_production_report.pdf');
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function getReportExcel(Request $request): BinaryFileResponse
    {
//        $date = $request->get('date', date('Y-m-d'));
//        $buyer = $request->get('buyer_id', null);
//        $reportData = (new FinishingProductionReportV2($date, $buyer))->generateReport();

        $fromDate = $request->get('from_date', date('Y-m-d'));
        $toDate = $request->get('to_date', date('Y-m-d'));
        $buyer = $request->get('buyer_id');
        $floorId = $request->get('floor_id');
        $limit = 0;
        $offset = 0;
        $reportData = (new FinishingProductionReportV3($fromDate, $toDate, $buyer, $floorId))
            ->generateReport('EXCEL', $limit, $offset);

        return Excel::download(new FinishingProductionReportV2Export($reportData), 'finishing_production_report.xlsx');
    }
}
