<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Controllers\Reports;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Iedroplets\Export\WeeklyShipmentScheduleReportExport;
use SkylarkSoft\GoRMG\Iedroplets\PackageConst;
use SkylarkSoft\GoRMG\Iedroplets\Services\Reports\WeeklyShipmentScheduleService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class WeeklyShipmentScheduleReportController extends Controller
{
    public function index()
    {
        $buyers = Buyer::query()->get(['id', 'name']);

        return view(PackageConst::PACKAGE_NAME . '::reports.weekly_shipment_schedule', [
            'buyers' => $buyers,
        ]);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getWeekOfTheYear(Request $request): string
    {
        return "Wk-" . Carbon::parse($request->get('date'))->weekOfYear;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function reportData(Request $request)
    {
        $reportData['data'] = (new WeeklyShipmentScheduleService())->reportData($request);

        return view(PackageConst::PACKAGE_NAME . '::reports.includes.weekly_shipment_schedule_table', $reportData);
    }

    public function reportPdf(Request $request)
    {
        $reportData['data'] = (new WeeklyShipmentScheduleService())->reportData($request);
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView(PackageConst::PACKAGE_NAME . '::reports.downloads.pdf.weekly_shipment_schedule_pdf', $reportData)
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer')
            ]);

        return $pdf->stream('weekly_shipment_schedule.pdf');
    }

    public function reportExcel(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $reportData['data'] = (new WeeklyShipmentScheduleService())->reportData($request);

        return Excel::download(new WeeklyShipmentScheduleReportExport($reportData), 'weekly_shipment_schedule.xlsx');
    }
}
