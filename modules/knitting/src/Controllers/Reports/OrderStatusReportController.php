<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\Reports;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Knitting\Exports\OrderStatusReportExcel;
use SkylarkSoft\GoRMG\Knitting\Services\Reports\OrderStatusReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderStatusReportController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        if ($request->get('buyer_id')) {
            $data = (new OrderStatusReportService($request))->report();
        }

        $buyers = Buyer::query()
            ->where('factory_id', factoryId())
            ->get();

        return view('knitting::reports.order-status-report.index', compact('data', 'buyers'));
    }

    public function pdf(Request $request)
    {
        $data = [];
        if ($request->get('buyer_id')) {
            $data = (new OrderStatusReportService($request))->report();
        }

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('knitting::reports.order-status-report.pdf', compact('data'))
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('order-status-report' . '.pdf');
    }

    public function excel(Request $request): BinaryFileResponse
    {
        $data = [];
        if ($request->get('buyer_id')) {
            $data = (new OrderStatusReportService($request))->report();
        }

        return Excel::download(new OrderStatusReportExcel(compact('data')), 'order-status-report' . '.xlsx');
    }
}
