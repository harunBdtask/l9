<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers\Reports;

use App\Http\Controllers\Controller;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;
use SkylarkSoft\GoRMG\ManualProduction\Exports\DailySwingProductionExport;
use SkylarkSoft\GoRMG\ManualProduction\Services\DailySwingProductionReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DailySewingProductionReportController extends Controller
{
    public function index(Request $request)
    {
        $factory_id = $request->get('factory_id');
        $floor_id = $request->get('floor_id');
        $date = $request->get('date');

        $factories = Factory::query()->pluck('factory_name', 'id');
        $floors = $floor_id ? Floor::query()->withoutGlobalScope('factoryId')
            ->where('id', $floor_id)
            ->pluck('floor_no', 'id') : [];

        $data = DailySwingProductionReportService::data($request);
        $metaData = DailySwingProductionReportService::metaData($request);
        return view('manual-production::reports.dailySewingProductionReport.index', compact(
            'factories',
            'floors',
            'factory_id',
            'floor_id',
            'date',
            'data',
            'metaData'
        ));
    }

    public function pdf(Request $request)
    {
        $data = DailySwingProductionReportService::data($request);
        $metaData = DailySwingProductionReportService::metaData($request);
        $pdf = PDF::loadView('manual-production::reports.dailySewingProductionReport.pdf', compact(
            'data',
            'metaData'
        ));
        $pdf->setPaper('A4')->setOrientation('landscape');
        return $pdf->stream("daily_swing_production_report.pdf");
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function excel(Request $request): BinaryFileResponse
    {
        return Excel::download(new DailySwingProductionExport($request), 'daily_sewing_production.xlsx');
    }
}
