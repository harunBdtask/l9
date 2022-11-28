<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\Reports;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Knitting\Exports\DailyProductionReportExcel;
use SkylarkSoft\GoRMG\Knitting\Services\Reports\DailyProductionReportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DailyProductionReportController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        if ($request->get('from_date') && $request->get('to_date')) {
            $data = (new DailyProductionReportService($request))->report();
        }
        return view('knitting::reports.daily-production-report.index', compact('data'));
    }

    public function pdf(Request $request)
    {
        $data = [];
        if ($request->get('from_date') && $request->get('to_date')) {
            $data = (new DailyProductionReportService($request))->report();
        }

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('knitting::reports.daily-production-report.pdf', compact('data'))
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('daily-production-report' . '.pdf');
    }

    public function excel(Request $request): BinaryFileResponse
    {
        $data = [];
        if ($request->get('from_date') && $request->get('to_date')) {
            $data = (new DailyProductionReportService($request))->report();
        }

        return Excel::download(new DailyProductionReportExcel(compact('data')), 'daily-production-report' . '.xlsx');
    }
}
