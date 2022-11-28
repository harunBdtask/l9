<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Reports;


use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Services\Report\ReportService;

class OrderRecapReportController extends Controller
{
    public function index(Request $request)
    {
        $data = ReportService::getOrderRecapReport($request);
        return view("merchandising::reports.order_recap.index", $data);
    }

    public function pdf(Request $request)
    {
        $data = ReportService::getOrderRecapReport($request);
        $pdf = PDF::loadView('merchandising::reports.order_recap.pdf', $data)->setPaper('a4', 'landscape');

        return $pdf->stream('order_recap.pdf');
    }
}
