<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers\Reports;

use App\Http\Controllers\Controller;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\ManualProduction\Exports\DailySewingOutputReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateWiseSewingReport;
use SkylarkSoft\GoRMG\ManualProduction\Services\DailySwingProductionReportService;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BuyerStyleColorWiseDailySewingOutputReportController extends Controller
{
    public function index(Request $request)
    {
        $factories = Factory::query()->get(['id', 'factory_name as name']);
        $data = DailySewingOutputReport::data($request);
        $metaData = DailySewingOutputReport::metaData($request);
        return view('manual-production::reports.dailySewingOutputReport.index', compact('factories', 'data', 'metaData'));
    }

    public function pdf(Request $request)
    {
        $factories = Factory::query()->get(['id', 'factory_name as name']);
        $data = DailySewingOutputReport::data($request);
        $metaData = DailySewingOutputReport::metaData($request);
        $pdf = PDF::loadView('manual-production::reports.dailySewingOutputReport.pdf', compact(
            'data',
            'metaData', 'factories'
        ));
        $pdf->setPaper('A4')->setOrientation('landscape');
        return $pdf->stream("Daily Sewing Output Report.pdf");
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function excel(Request $request): BinaryFileResponse
    {
        return Excel::download(new DailySewingOutputReport($request), 'Daily Sewing Output.xlsx');
    }
}
