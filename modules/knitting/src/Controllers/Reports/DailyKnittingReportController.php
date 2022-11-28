<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\Reports;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Knitting\Exports\DailyKnittingReportExcel;
use SkylarkSoft\GoRMG\Knitting\Services\Reports\DailyKnittingReportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DailyKnittingReportController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        if ($request->get('from_date') && $request->get('to_date')) {
            $data = (new DailyKnittingReportService($request))->report();
        }
        return view('knitting::reports.daily-knitting-report.index', compact('data'));
    }

    public function pdf(Request $request)
    {
        $data = [];
        if ($request->get('from_date') && $request->get('to_date')) {
            $data = (new DailyKnittingReportService($request))->report();
        }

        $pdf = PDF::loadView('knitting::reports.daily-knitting-report.pdf', compact('data'))
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('daily-knitting-report-' . date('d-m-Y', strtotime($request->get('date'))) . '.pdf');
    }

    public function excel(Request $request): BinaryFileResponse
    {
        $data = [];
        if ($request->get('from_date') && $request->get('to_date')) {
            $data = (new DailyKnittingReportService($request))->report();
        }

        return Excel::download(new DailyKnittingReportExcel(compact('data')), 'daily-knitting-report-' . date('d-m-Y', strtotime($request->get('date'))) . '.xlsx');
    }
}
