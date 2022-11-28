<?php

namespace SkylarkSoft\GoRMG\TQM\Controllers;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\TQM\Exports\DhuReportExport;
use SkylarkSoft\GoRMG\TQM\Services\DhuReportStrategy;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DhuReportController extends Controller
{
    public function index()
    {
        $type = 'Cutting';
        return view('tqm::reports.dhu-report.index', compact('type'));
    }

    public function getReport(Request $request)
    {
        $data = $this->reportData($request);

        return view('tqm::reports.dhu-report.table', compact('data'));
    }

    public function pdf(Request $request)
    {
        $type = $request->get('type');
        $data = $this->reportData($request);

        $pdf = PDF::setOption('enable-local-file-access', true)->loadView('tqm::reports.dhu-report.pdf',
            compact('data', 'type')
        )->setPaper('a4')->setOrientation('landscape')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);
        return $pdf->stream($type . '-dhu-report.pdf');
    }

    public function excel(Request $request): BinaryFileResponse
    {
        $type = $request->get('type');
        $data = $this->reportData($request);

        return Excel::download(new DhuReportExport($data, $type), $type . '-dhu-report.xlsx');
    }

    private function reportData($request)
    {
        return (new DhuReportStrategy())->setType($request->get('type'))
            ->setFromDate($request->get('from_date'))
            ->setToDate($request->get('to_date'))
            ->generate();
    }
}
