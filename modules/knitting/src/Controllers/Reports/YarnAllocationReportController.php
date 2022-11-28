<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\Reports;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use PDF;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use SkylarkSoft\GoRMG\Knitting\Exports\YarnAllocationReportExcel;
use SkylarkSoft\GoRMG\Knitting\Services\Reports\YarnAllocationReportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class YarnAllocationReportController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        if ($request->get('from_date') && $request->get('to_date')) {
            $data = (new YarnAllocationReportService($request))->report();
        }
        return view('knitting::reports.yarn-allocation-report.index', compact('data'));
    }

    public function pdf(Request $request)
    {
        $data = [];
        if ($request->get('from_date') && $request->get('to_date')) {
            $data = (new YarnAllocationReportService($request))->report();
        }

        $pdf = PDF::loadView('knitting::reports.yarn-allocation-report.pdf', compact('data'))
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('yarn-allocation-report-' . date('d-m-Y', strtotime($request->get('date'))) . '.pdf');
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function excel(Request $request): BinaryFileResponse
    {
        $data = [];
        if ($request->get('from_date') && $request->get('to_date')) {
            $data = (new YarnAllocationReportService($request))->report();
        }

        return Excel::download(new YarnAllocationReportExcel(compact('data')), 'yarn-allocation-report-' . date('d-m-Y', strtotime($request->get('date'))) . '.xlsx');
    }
}
