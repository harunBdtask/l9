<?php

namespace SkylarkSoft\GoRMG\Washingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Washingdroplets\Exports\AllOrdersWashingReportExport;
use SkylarkSoft\GoRMG\Washingdroplets\Exports\BuyerWiseWashingReportExport;
use SkylarkSoft\GoRMG\Washingdroplets\Models\Washing;
use SkylarkSoft\GoRMG\Washingdroplets\Models\WashingReceive;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use Carbon\Carbon;

class OrderColorSizeReportController extends Controller
{
    public function getAllOrderWiseReport()
    {
        $order_wise_report = $this->getAllOrderWiseReportData(PAGINATION);
        return view('washingdroplets::reports.order_wise_wasing_received_summary', [
            'order_wise_report' => $order_wise_report,
            'print' => 0,
        ]);
    }

    public function getAllOrderWiseReportData($pagination)
    {
        return TotalProductionReport::with('order', 'purchaseOrder', 'buyer')
            ->orderBy('buyer_id', 'desc')
            ->paginate($pagination);
    }

    public function getAllOrderWiseReportDataForDownload($page)
    {
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $total_production_report = TotalProductionReport::with('order', 'purchaseOrder', 'buyer')
            ->orderBy('buyer_id', 'desc')
            ->paginate(PAGINATION);
        return $total_production_report;
    }

    public function getAllOrderWiseReportDownload($type, $page)
    {
        if ($type == 'pdf') {
            $data['order_wise_report'] = $this->getAllOrderWiseReportDataForDownload($page);
            $data['print'] = 1;
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('washingdroplets::reports.downloads.pdf.order-wise-received-from-wash-report-download',
                    $data, [], ['format' => 'A4-L']
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('order-wise-received-from-wash-report.pdf');
        } else {
            return \Excel::download(new AllOrdersWashingReportExport(), 'order-wise-received-from-wash-report.xlsx');
        }
    }

    public function getBuyerWiseReport(Request $request)
    {
        $buyers = Buyer::pluck('name', 'id')->all();

        $reportdata = null;
        if ($request->isMethod('post')) {
            $reportdata = $this->getBuyerWiseWashingReport($request->buyer_id, 1);
        }
        if ($request->isMethod('get')) {
            $reportdata = $this->getBuyerWiseWashingReport($request->buyer_id, $request->page);
        }
        return view('washingdroplets::reports.buyer_wise_received_report', [
            'buyers' => $buyers,
            'reportdata' => $reportdata,
            'print' => 0,
            'buyer_id' => $request->buyer_id
        ]);
    }

    public function getBuyerWiseReportView($buyer_id)
    {
        $order_wise_report = PurchaseOrder::with('todayWashingSent', 'todayWashingReceived', 'tatalWashingSent', 'tatalWashingReceived')
            ->where('buyer_id', $buyer_id)
            ->orderBy('id', 'desc')
            ->get();

        $result = [];
        $total_order_qty = 0;
        $total_cutting_qty = 0;
        $total_rejection = 0;
        $total_today_input = 0;
        $total_total_input = 0;
        $total_today_output = 0;
        $total_total_output = 0;
        $total_today_wash_send = 0;
        $total_today_wash_receive = 0;
        $total_total_wash_send = 0;
        $total_total_received = 0;
        $total_report = [];

        foreach ($order_wise_report as $key => $order) {
            $order_wise_report[$key]->style_name = $order->order->order_style_no ?? '';
            $order_wise_report[$key]->order_qty = $order->po_quantity;

            // today
            $today_wash_send = $order->todayWashingSent->sum('bundlecard.quantity')
                - $order->todayWashingSent->sum('bundlecard.total_rejection')
                - $order->todayWashingSent->sum('bundlecard.print_rejection')
                - $order->todayWashingSent->sum('bundlecard.sewing_rejection');

            $order_wise_report[$key]->today_wash_send = $today_wash_send;

            $today_wash_receive = $order->todayWashingReceived->sum('bundlecard.quantity')
                - $order->todayWashingReceived->sum('bundlecard.total_rejection')
                - $order->todayWashingReceived->sum('bundlecard.print_rejection')
                - $order->todayWashingReceived->sum('bundlecard.sewing_rejection')
                - $order->todayWashingReceived->sum('bundlecard.washing_rejection');

            $order_wise_report[$key]->today_wash_receive = $today_wash_receive;

            // total
            $total_wash_send = $order->tatalWashingSent->sum('bundlecard.quantity')
                - $order->tatalWashingSent->sum('bundlecard.total_rejection')
                - $order->tatalWashingSent->sum('bundlecard.print_rejection')
                - $order->tatalWashingSent->sum('bundlecard.sewing_rejection');

            $order_wise_report[$key]->total_wash_send = $total_wash_send;

            $total_wash_receive = $order->tatalWashingReceived->sum('bundlecard.quantity')
                - $order->tatalWashingReceived->sum('bundlecard.total_rejection')
                - $order->tatalWashingReceived->sum('bundlecard.print_rejection')
                - $order->tatalWashingReceived->sum('bundlecard.sewing_rejection')
                - $order->tatalWashingReceived->sum('bundlecard.washing_rejection');

            $order_wise_report[$key]->total_wash_receive = $total_wash_receive;

            $washing_rejection = $order->tatalWashingReceived->sum('bundlecard.washing_rejection');
            $order_wise_report[$key]->washing_rejection = $washing_rejection;

            $cutting_qty = 0;
            $today_input = 0;
            $total_input = 0;
            $today_output = 0;
            $total_output = 0;

            $cutting_qty = $order->bundleCards->sum('quantity') - $order->bundleCards->sum('total_rejection');
            foreach ($order->bundleCards as $bundle) {
                // input
                if (isset($bundle->cutting_inventory->cutting_inventory_challan->line_id)) {
                    if ($bundle->cutting_inventory->cutting_inventory_challan->input_date == date('Y-m-d')) {
                        $today_input += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection;
                    }
                    $total_input += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection;
                }
                //output
                if (isset($bundle->sewingoutput)) {
                    if (date('Y-m-d', strtotime($bundle->sewingoutput->created_at)) == date('Y-m-d')) {
                        $today_output += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection - $bundle->sewing_rejection;
                    }
                    $total_output += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection - $bundle->sewing_rejection;
                }
            }

            $order_wise_report[$key]->cutting_qty = $cutting_qty;
            $order_wise_report[$key]->today_input = $today_input;
            $order_wise_report[$key]->total_input = $total_input;
            $order_wise_report[$key]->today_output = $today_output;
            $order_wise_report[$key]->total_output = $total_output;
            //$order_wise_report[$key]->total_rejection = $total_rejection;

            $total_order_qty += $order->total_quantity;
            $total_cutting_qty += $cutting_qty;
            $total_rejection += $washing_rejection;
            $total_today_input += $today_input;
            $total_total_input += $total_input;
            $total_today_output += $today_output;
            $total_total_output += $total_output;
            $total_today_wash_send += $today_wash_send;
            $total_today_wash_receive += $today_wash_receive;
            $total_total_wash_send += $total_wash_send;
            $total_total_received += $total_wash_receive;

            $total_report['total_order_qty'] = $total_order_qty;
            $total_report['total_cutting_qty'] = $total_cutting_qty;
            $total_report['total_rejection'] = $total_rejection;
            $total_report['total_today_input'] = $total_today_input;
            $total_report['total_total_input'] = $total_total_input;
            $total_report['total_today_output'] = $total_today_output;
            $total_report['total_total_output'] = $total_total_output;
            $total_report['total_today_wash_send'] = $total_today_wash_send;
            $total_report['total_today_wash_receive'] = $total_today_wash_receive;
            $total_report['total_total_wash_send'] = $total_total_wash_send;
            $total_report['total_total_received'] = $total_total_received;
        }

        $result['order_wise_report'] = $order_wise_report;
        $result['total_report'] = $total_report;

        return $result;
    }

    public function getBuyerWiseWashingReport($buyer_id, $page)
    {
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        return TotalProductionReport::with('purchaseOrder', 'order')
            ->where('buyer_id', $buyer_id)
            ->orderBy('purchase_order_id')->paginate(18);
    }

    public function getBuyerWiseReportDownload($type, $buyer_id, $page)
    {
        if ($type == 'pdf') {
            $data['reportdata'] = $this->getBuyerWiseWashingReport($buyer_id, $page);
            $data['print'] = 1;
            $data['buyer'] = Buyer::where('id', $buyer_id)->first()->name ?? '';
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('washingdroplets::reports.downloads.pdf.buyer-wise-received-from-wash-report-download',
                    $data, [], ['format' => 'A4-L']
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('buyer-wise-received-from-wash-report.pdf');
        } else {
            return \Excel::download(new BuyerWiseWashingReportExport($buyer_id), 'buyer-wise-received-from-wash-report.xlsx');

            /*\Excel::create('Buyer Wise Washing Report ', function ($excel) use ($data) {
                $excel->sheet('Buyer Wise Washing Report', function ($sheet) use ($data) {
                    $sheet->loadView('washingdroplets::reports.downloads.excels.buyer-wise-received-from-wash-report-download', $data);
                });
            })->export('xls');*/
        }
    }

    public function sizeWiseReportForm()
    {
        $buyers = Buyer::pluck('name', 'id')->all();
        return view('washingdroplets::reports.sewing-module.size-wise-report', [
            'buyers' => $buyers
        ]);
    }

    public function sizeWiseReportReport($purchase_order_id)
    {
        $result = [];
        $total_rejection = 0;
        $total_today_input = 0;
        $total_total_input = 0;
        $total_today_output = 0;
        $total_total_output = 0;
        $total_wip = 0;
        $total_in_line_wip = 0;

        $report_size_wise = [];
        $total_report = [];
        $order_sizes = PurchaseOrderDetail::where('purchase_order_id', $purchase_order_id)->get();
        foreach ($order_sizes as $key => $size) {
            $report_size_wise[$key]['color'] = $size->color->name;
            $report_size_wise[$key]['size'] = $size->size->name;
            $report_size_wise[$key]['size_order_qty'] = $size->quantity;

            $bundles_gen_details = BundleCardGenerationDetail::where(['color_id' => $size->color_id, 'purchase_order_id' => $purchase_order_id])
                ->with('bundleCards')
                ->get();

            $size_cutting_qty = 0;
            $today_input = 0;
            $total_input = 0;
            $today_output = 0;
            $total_output = 0;
            $rejection = 0;
            foreach ($bundles_gen_details as $bundle_details) {
                foreach ($bundle_details->bundleCards as $bundle) {
                    // size wise cutting qty
                    if ($bundle->size_id == $size->size_id) {
                        $size_cutting_qty += $bundle->quantity;
                        $rejection += $bundle->total_rejection + $bundle->print_rejection + $bundle->sewing_rejection;

                        // today & total input to line
                        if (isset($bundle->cutting_inventory->cutting_inventory_challan->line_id)) {
                            if ($bundle->cutting_inventory->cutting_inventory_challan->input_date == date('Y-m-d')) {
                                $today_input += $bundle->quantity;
                            }
                            $total_input += $bundle->quantity;
                        }
                        // today & total sewing output
                        if (isset($bundle->sewingoutput)) {
                            if (date('Y-m-d', strtotime($bundle->sewingoutput->created_at)) == date('Y-m-d')) {
                                $today_output += $bundle->quantity;
                            }
                            $total_output += $bundle->quantity;
                        }
                    }
                }
            }

            $report_size_wise[$key]['size_cutting_qty'] = $size_cutting_qty;
            $wip = $size_cutting_qty - $total_input;
            $report_size_wise[$key]['wip'] = $wip;
            $report_size_wise[$key]['today_input'] = $today_input;
            $report_size_wise[$key]['total_input'] = $total_input;
            $report_size_wise[$key]['today_output'] = $today_output;
            $report_size_wise[$key]['total_output'] = $total_output;
            $report_size_wise[$key]['rejection'] = $rejection;
            $in_line_wip = $total_input - $total_output;
            $report_size_wise[$key]['in_line_wip'] = $in_line_wip;
            $report_size_wise[$key]['cutt_sewing_ratio'] = ($total_output > 0 && $size_cutting_qty > 0) ? number_format(($total_output / $size_cutting_qty) * 100, 2) : 0;

            // total row of report
            $total_rejection += $rejection;
            $total_today_input += $today_input;
            $total_total_input += $total_input;
            $total_today_output += $today_output;
            $total_total_output += $total_output;
            $total_wip += $wip;
            $total_in_line_wip += $in_line_wip;
        }

        $total_report['total_wip'] = $total_wip;
        $total_report['total_today_input'] = $total_today_input;
        $total_report['total_total_input'] = $total_total_input;
        $total_report['total_today_output'] = $total_today_output;
        $total_report['total_total_output'] = $total_today_output;
        $total_report['total_rejection'] = $total_rejection;
        $total_report['total_in_line_wip'] = $total_in_line_wip;

        $result['report_size_wise'] = $report_size_wise;
        $result['total_report'] = $total_report;

        return $result;
    }

    public function colorWashingWiseReport(Request $request)
    {
        $styles = null;
        $orders = null;
        $pdf_url = null;
        $excel_url = null;

        if (!$request->has('form_submit')) {
            $reportData = null;
        } else{

            $request->validate([
                'buyer_id' => 'required',
                'order_id' => 'required',
                'purchase_order_id' => 'required'
            ]);
            $buyer_id = $request->buyer_id;
            $order_id = $request->order_id;
            $purchase_order_id = $request->purchase_order_id;

            $reportData = TotalProductionReport::with('buyer','purchaseOrder','order','colors')
                ->where([
                    'buyer_id' => $request->buyer_id,
                    'order_id' => $request->order_id,
                    'purchase_order_id' => $request->order_id
                ])->get();

            $styles = Order::where('buyer_id', $request->buyer_id)
                    ->pluck('order_style_no', 'id')
                    ->all();

            $orders = PurchaseOrder::where('order_id', $request->style_id)
                    ->pluck('po_no', 'id')
                    ->all();
            $pdf_url = '/color-wise-washing-report-download/'.$buyer_id.'/'.$order_id.'/'.$purchase_order_id.'/pdf';
            $excel_url = '/color-wise-washing-report-download/'.$buyer_id.'/'.$order_id.'/'.$purchase_order_id.'/excel';
        }

        $buyers = Buyer::pluck('name', 'id')->all();

        return view('washingdroplets::reports.color_wise_washing_report', [
            'buyers' => $buyers,
            'styles' => $styles,
            'orders' => $orders,
            'reportData' => $reportData,
            'pdf_url' => $pdf_url,
            'excel_url' => $excel_url,
            'reportParameter' => $request->all()
        ]);
    }

    public function colorWashingWiseReportDownload($buyer_id, $order_id, $purchase_order_id, $type)
    {
        $data['reportData'] = TotalProductionReport::with('buyer','purchaseOrder','order','colors')->where(['buyer_id' => $buyer_id, 'order_id' => $order_id, 'purchase_order_id' => $purchase_order_id])->get();
        $data['buyer'] = Buyer::where('id',$buyer_id)->first()->name ?? '';
        $data['order_style_no'] = Order::where('id',$order_id)->first()->order_style_no ?? '';
        $data['po_no'] = PurchaseOrder::where('id',$purchase_order_id)->first()->po_no ?? '';
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('washingdroplets::reports.downloads.pdf.color_wise_washing_report_download',
                    $data, [], ['format' => 'A4-L']
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('color-wise-wash-report.pdf');
        } else {
            \Excel::create('Color Wise Washing Report ', function ($excel) use ($data) {
                $excel->sheet('Color Wise Washing Report', function ($sheet) use ($data) {
                    $sheet->loadView('washingdroplets::reports.downloads.excels.color_wise_washing_report_download', $data);
                });
            })->export('xls');
        }
    }

    // For TNA
    public static function getOrderWiseActualWashingDateInfo($order_id)
    {
        $bundleCardQuery = BundleCard::where(['order_id' => $order_id, 'status' => 1])->whereNotNull('washing_date');
        $actual_start = '';
        $actual_end = '';
        $duration = '';
        if($bundleCardQuery->count()) {
            $order_qty = Order::findOrFail($order_id)->total_quantity;
            $firstBundle = $bundleCardQuery->orderBy('washing_date', 'asc')->first();
            $actual_start = $firstBundle->washing_date;
            $bundleCardQueryClone = clone $bundleCardQuery;
            $totalCuttingQty = $bundleCardQueryClone->sum('quantity') - $bundleCardQueryClone->sum('total_rejection') - $bundleCardQueryClone->sum('print_rejection') - ($bundleCardQueryClone->sum('embroidary_rejection') ?? 0) - $bundleCardQueryClone->sum('sewing_rejection') - $bundleCardQueryClone->sum('washing_rejection');
            $lastBundle = $bundleCardQueryClone->orderBy('washing_date', 'desc')->first();
            if ($totalCuttingQty >= $order_qty) {
                $actual_end = $lastBundle->washing_date;
                $duration = calculateDays($actual_start, $actual_end);
            }
        }

        return [
            'actual_start' => $actual_start,
            'actual_end' => $actual_end,
            'actual_duration' => $duration,
        ];
    }

}
