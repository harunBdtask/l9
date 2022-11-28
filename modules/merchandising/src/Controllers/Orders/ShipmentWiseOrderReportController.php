<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Merchandising\Services\Month;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\Merchandising\Services\Report\ReportViewService;
use SkylarkSoft\GoRMG\Merchandising\Exports\ShipmentWiseOrderReportExcel;
use SkylarkSoft\GoRMG\Merchandising\Services\Order\ShipmentWiseOrderReportService;

class ShipmentWiseOrderReportController extends Controller
{
    public function index(Request $request)
    {
        $reportData = ShipmentWiseOrderReportService::reportData($request);
        $reportChartData=collect($reportData['orders'])->map(function ($item){
            return ['key'=>$item['buyer_name'], 'value'=>$item['order_value']];
        })->toArray();
        $reportData['months'] = Month::months();

        $level = collect($reportChartData)->pluck('key')->toArray();
        $value = collect($reportChartData)->pluck('value')->toArray();
        $reportData['chart'] = ReportViewService::for('chart')
            ->setChartType('bar')
            ->setChartLevel($level)
            ->setChartValues($value)
            ->render();

        return view('merchandising::order.report.shipment_wise_report.shipment_wise_order_report', $reportData);
    }

    public function pdf(Request $request): Response
    {
        $reportData = ShipmentWiseOrderReportService::reportData($request);
        $signature = ReportSignatureService::getSignatures("SHIPMENT WISE ORDER REPORT");
        $pdf = PDF::loadView('merchandising::order.report.shipment_wise_report.shipment_wise_order_report_pdf', $reportData, ['signature' => $signature])
            ->setOptions([
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);

        return $pdf->download('shipment_wise_order_report.pdf');
    }

    public function excel(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $reportData = ShipmentWiseOrderReportService::reportData($request);

        return \Excel::download(new ShipmentWiseOrderReportExcel($reportData), 'shipment_wise_order_report.xlsx');
    }
}
