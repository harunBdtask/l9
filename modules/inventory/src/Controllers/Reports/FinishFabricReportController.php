<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\Reports;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Inventory\Exports\FinishFabricReportExport;
use SkylarkSoft\GoRMG\Inventory\Services\Reports\FinishFabricStoreReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FinishFabricReportController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $buyers = Buyer::query()->pluck('name', 'id');
        $buyers->prepend('Select Buyer', 0);
        return view('inventory::reports.finish_fabric_store_report.index', compact('buyers'));
    }

    /**
     * @param Request $request
     * @return View
     */
    public function getReport(Request $request): View
    {
        $reportData = (new FinishFabricStoreReportService($request))->report();
        return view('inventory::reports.finish_fabric_store_report.table', compact('reportData'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getReportPdf(Request $request)
    {
        $reportData = (new FinishFabricStoreReportService($request))->report();
        $pdf = PDF::loadView('inventory::reports.finish_fabric_store_report.pdf',
            compact('reportData'))
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('finish_fabric_store_report.pdf');
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $reportData = (new FinishFabricStoreReportService($request))->report();
        return Excel::download(new FinishFabricReportExport($reportData), 'finish_fabric_report_export.xlsx');
    }
}
