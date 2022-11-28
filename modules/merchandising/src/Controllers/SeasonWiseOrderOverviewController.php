<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Exports\SeasonWiseOrderOverviewExport;
use SkylarkSoft\GoRMG\Merchandising\Services\SeasonWiseOrderOverviewReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;

class SeasonWiseOrderOverviewController extends Controller
{
    public function view()
    {
        $seasons = Season::query()->get()->pluck('season_name')->unique()->values();
        $year = request()->get('year')  ?? Carbon::today()->format('Y');
        $season = request()->get('season') ?? null;
        $orders = [];

        if ($season) {
            $orders = SeasonWiseOrderOverviewReportService::reportData($year, $season);
        }

        return view('merchandising::order.overview_report.season_wise_order_overview', compact('seasons', 'year', 'orders'));
    }

    public function getReportPdf()
    {
        $season = request()->get('season') ?? null;
        $year = request()->get('year')  ?? Carbon::today()->format('Y');

        $orders = SeasonWiseOrderOverviewReportService::reportData($year, $season);

        $pdf = PDF::setOption('enable-local-file-access', true)->loadView('merchandising::order.overview_report.season_wise_order_overview_pdf', compact('orders'))
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header', ['name' => 'Buyer Season Order List']),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream($season . '_list.pdf');
    }

    public function getReportExcel(Request $request)
    {
        $season = request()->get('season') ?? null;
        $year = request()->get('year')  ?? Carbon::today()->format('Y');
        $orders = SeasonWiseOrderOverviewReportService::reportData($year, $season);
//        return $orders;

        return Excel::download(new SeasonWiseOrderOverviewExport($orders), $season . '_list.xlsx');
    }

}
