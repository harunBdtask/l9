<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\Reports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\Collection;
use SkylarkSoft\GoRMG\Inventory\Exports\FabricStockSummaryReportExport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceive;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricStockSummeryReport\FabricStockSummeryService;
use PDF;

class FabricStockSummeryReportController extends Controller
{
    public function index()
    {
        $buyers = Buyer::query()->pluck('name', 'id');
        $buyers->prepend('Select Buyer', 0);

        return view('inventory::reports.fabric_stock_summery_report.index', [
            'buyers' => $buyers,
        ]);
    }

    public function reportData(Request $request)
    {
        $reportData = (new FabricStockSummeryService())->reportData($request);

        return view('inventory::reports.fabric_stock_summery_report.table', ['fabricStockSummary' => $reportData]);
    }

    public function pdf(Request $request)
    {
        $reportData['fabricStockSummary'] = (new FabricStockSummeryService())->reportData($request);

        $pdf = PDF::loadView('inventory::reports.fabric_stock_summery_report.pdf',
            $reportData)
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('fabric_stock_summery_report.pdf');
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function excel(Request $request)
    {
        $reportData['fabricStockSummary'] = (new FabricStockSummeryService())->reportData($request);

        return Excel::download(new FabricStockSummaryReportExport($reportData), 'fabric_stock_summery_report.xlsx');

    }
}
