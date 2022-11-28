<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Printembrdroplets\Exports\BuyerWisePrintReportExport;
use SkylarkSoft\GoRMG\Printembrdroplets\Exports\CuttingWisePrintReportExport;
use SkylarkSoft\GoRMG\Printembrdroplets\Exports\DateWisePrintReportExport;
use SkylarkSoft\GoRMG\Printembrdroplets\Exports\PoWisePrintReportExport;
use SkylarkSoft\GoRMG\Printembrdroplets\Exports\StyleWisePrintReportExport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintEmbrProductionReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintProductionReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use Carbon\Carbon;
use Session;

class ColorAndSizeReportController extends Controller
{
    public function getBuyerWiseSendReceivedForm()
    {
        $buyers = Buyer::pluck('name', 'id')->all();
        return view('printembrdroplets::reports.color_print_send_receive_report')->with('buyers', $buyers);
    }

    public function getBuyerWiseSendReceivedPost(Request $request)
    {
        $buyer_id = $request->buyer_id;
        $total_production_report_query = TotalProductionReport::with([
            'order:id,style_name',
            'purchaseOrder:id,po_no,po_quantity'
        ])
        ->where('buyer_id', $buyer_id)
        ->orderBy('purchase_order_id');

        if ($total_production_report_query->count() > 0) {
            $total_production_report = $total_production_report_query->paginate(18);
            $buyer_wise_report_html = '';
            $buyer_wise_report_html .= '<tr class="tr-height">';
            $buyer_wise_report_html .= '<td colspan="11" class="text-center text-danger">No Data</td>';
            $buyer_wise_report_html .= '</tr>';
            if ($request->ajax()) {
                $print = 0; // No Print
                $buyer_wise_report_html = view('printembrdroplets::reports.includes.buyer_wise_report_inc', array('total_production_report' => $total_production_report, 'print' => $print))->render();
            }
            $status = 200;
            return response()->json(['status' => $status, 'html' => $buyer_wise_report_html, 'buyer_id' => $buyer_id]);
        } else {
            $html = '';
            $order_info_data = null;
            $html .= '<tr class="tr-height">';
            $html .= '<td colspan="11" colspan="11" class="text-center text-danger">No Data</td>';
            $html .= '</tr>';
            $status = 500;
            return response()->json(['status' => $status, 'html' => $html, 'buyer_id' => $buyer_id]);
        }
    }

    public function getBuyerWisePrintSendReceiveDownload($type, $buyer_id, $page)
    {
        $data['total_production_report'] = $this->getBuyerWiseSendReceivedReportForDownload($buyer_id, $page);
        $data['print'] = 1;
        $data['buyer'] = Buyer::where('id', $buyer_id)->first()->name ?? '';
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('printembrdroplets::reports.downloads.pdf.buyer-wise-print-send-receive-report-download', $data)
                ->setPaper('a4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('buyer-wise-print-send-receive-report.pdf');
        } else {
            return \Excel::download(new BuyerWisePrintReportExport($buyer_id), 'buyer-wise-print-send-receive-report.xlsx');
        }
    }

    private function getBuyerWiseSendReceivedReportForDownload($buyer_id, $page)
    {
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $total_production_report = TotalProductionReport::with([
            'order:id,style_name',
            'purchaseOrder:id,po_no,po_quantity',
            'colors:id,name'
        ])
        ->where('buyer_id', $buyer_id)
        ->orderBy('purchase_order_id')
        ->paginate(18);

        return $total_production_report;
    }

    public function getCuttingNoWisePrintReport()
    {
        return view('printembrdroplets::reports.cutting_no_wise_report');
    }

    public function getCuttingNoWisePrintReportPost($buyer_id, $purchase_order_id, $color_id, $cutting_no)
    {
        $result_report = [];
        $order_sizes = PurchaseOrderDetail::with([
            'purchaseOrder:id,po_no,po_quantity',
            'color:id,name'
        ])
        ->where('purchase_order_id', $purchase_order_id)
        ->get();

        foreach ($order_sizes as $key => $size_details) {
            $result_report[$key]['color_name'] = $size_details->color->name ?? 'N/A';
            $result_report[$key]['size_name'] = $size_details->size->name ?? 'N/A';
            $bundlecard_data = BundleCard::with([
                'purchaseOrder:id,po_no,po_quantity',
                'size:id,name'
            ])->where([
                'purchase_order_id' => $purchase_order_id,
                'color_id' => $color_id,
                'cutting_no' => $cutting_no,
                'size_id' => $size_details->size_id,
                'status' => 1
            ])->get();

            $result_report[$key]['order_qty'] = $size_details->quantity;

            $cutting_qty = 0;
            $bundle_received = 0;
            $print_received_qty = 0;
            $print_send_qty = 0;
            $bundle_send = 0;
            $total_rejection = 0;
            $print_rejection = 0;
            $fabric_rejection = 0;

            foreach ($bundlecard_data as $key1 => $bundle) {
                if (isset($bundle->cutting_inventory) && $bundle->cutting_inventory->print_status == 1) {
                    $bundle_received++;
                    $print_received_qty += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection;
                    $print_rejection += $bundle->print_rejection;
                }
                if (isset($bundle->print_inventory)) {
                    $bundle_send++;
                    $print_send_qty += $bundle->quantity - $bundle->total_rejection;
                    $fabric_rejection += $bundle->total_rejection;
                }
            }

            $cutting_qty = $bundlecard_data->sum('quantity') - $bundlecard_data->sum('total_rejection');
            $result_report[$key]['cutting_qty'] = $cutting_qty;
            $result_report[$key]['cutting_wip'] = $cutting_qty - $print_send_qty;
            $result_report[$key]['bundle_send'] = $bundle_send;
            $result_report[$key]['total_send'] = $print_send_qty;

            $result_report[$key]['bundle_received'] = $bundle_received;
            $result_report[$key]['total_received'] = $print_received_qty;

            $result_report[$key]['fabric_rejection'] = $fabric_rejection;
            $result_report[$key]['print_rejection'] = $print_rejection;
            $result_report[$key]['total_rejection'] = $print_rejection + $fabric_rejection;
            $result_report[$key]['print_wip_short'] = $print_send_qty - $print_received_qty;
        }
        return $result_report;
    }

    public function getCuttingNoWisePrintReportDownload($type, $buyer_id, $purchase_order_id, $color_id, $cutting_no)
    {
        $data['result_report'] = $this->getCuttingNoWisePrintReportPost($buyer_id, $purchase_order_id, $color_id, $cutting_no);
        $data['buyer'] = Buyer::where('id', $buyer_id)->first()->name ?? '';
        $data['style_name'] = Order::where('buyer_id', $buyer_id)->first()->style_name ?? '';
        $data['po_no'] = PurchaseOrder::where('id', $purchase_order_id)->first()->po_no ?? '';
        $data['color'] = Color::where('id', $color_id)->first()->name ?? '';
        $data['cutting_no'] = $cutting_no;
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('printembrdroplets::reports.downloads.pdf.cutting-no-wise-print-send-receive-report-download', $data)
                ->setPaper('a4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('cutting-wise-print-send-receive-report.pdf');
        } else {
            return \Excel::download(new CuttingWisePrintReportExport($data), 'cutting-wise-print-send-receive-report.xlsx');
        }
    }

    public function getOrderWisePrintReport()
    {
        $buyers = Buyer::pluck('name', 'id')->all();
        return view('printembrdroplets::reports.order_wise_report')->with('buyers', $buyers);
    }

    public function getStyleWisePrintSendReceiveReport($order_id)
    {
        $total_production_report = TotalProductionReport::with('purchaseOrder')->where('order_id', $order_id)->get();
        $reports = [];
        foreach ($total_production_report->groupBy('order_id') as $key => $reportByOrder) {
            $reports[$key]['style'] = $reportByOrder->first()->order->order_style_no ?? '';
            $reports[$key]['po'] = $reportByOrder->first()->purchaseOrder->po_no ?? '';
            $order_total_qty = $reportByOrder->first()->purchaseOrder->po_quantity ?? 0;
            $reports[$key]['order_quantity'] = $order_total_qty;
            $total_cutting = 0;
            $total_cutting_rejection = 0;
            $total_sent = 0;
            $total_received = 0;
            $total_print_rejection = 0;
            foreach ($reportByOrder as $key2 => $report) {
                $total_cutting += $report->total_cutting ?? 0;
                $total_cutting_rejection += $report->total_cutting_rejection ?? 0;
                $total_sent += $report->total_sent ?? 0;
                $total_received += $report->total_received ?? 0;
                $total_print_rejection += $report->total_print_rejection ?? 0;
            }
            $reports[$key]['total_cutting'] = $total_cutting ?? 0;
            $reports[$key]['total_cutting_rejection'] = $total_cutting_rejection ?? 0;
            $reports[$key]['total_sent'] = $total_sent ?? 0;
            $reports[$key]['total_received'] = $total_received ?? 0;
            $reports[$key]['total_print_rejection'] = $total_print_rejection ?? 0;
            $reports[$key]['cutting_wip'] = $total_cutting - $total_sent ?? 0;
            $reports[$key]['print_wip'] = $total_sent - $total_received ?? 0;
        }
        return $reports;
    }

    public function getStyleWisePrintReportDownload($type, $buyer_id, $style_id)
    {
        $data['result_report'] = $this->getStyleWisePrintSendReceiveReport($style_id);
        $data['buyer'] = Buyer::where('id', $buyer_id)->first()->name ?? '';
        $data['order_style_no'] = Order::where('buyer_id', $buyer_id)->first()->order_style_no ?? '';
        if ($type == 'pdf') {
            $pdf = \PDF::loadView('printembrdroplets::reports.downloads.pdf.style-wise-print-send-receive-report-download', $data, [], [
                'format' => 'A4-L'
            ]);
            return $pdf->download('style-wise-print-send-receive-report.pdf');
        } else {
            return \Excel::download(new StyleWisePrintReportExport($data), 'style-wise-print-send-receive-report.xlsx');

            /*\Excel::create('Style Wise Print Send Receive Report', function ($excel) use ($data) {
                $excel->sheet('Style Wise Print Report Sheet', function ($sheet) use ($data) {
                    $sheet->loadView('printembrdroplets::reports.downloads.excel.style-wise-print-send-receive-report-download', $data);
                });
            })->export('xls');*/
        }
    }

    public function getOrderWisePrintReportData($buyer_id, $purchase_order_id)
    {
        $result = [];
        $order_wise_data = [];
        $total_data = [];

        $total_order_qty = 0;
        $total_cutting_qty = 0;
        $total_wip_qty = 0;
        $total_sent_qty = 0;
        $total_received_qty = 0;
        $total_print_wip = 0;
        $total_fabric_rejection = 0;
        $total_print_rejection = 0;
        $total_rejections = 0;

        $order_sizes = PurchaseOrderDetail::with('size', 'color')->where('purchase_order_id', $purchase_order_id)->orderBy('color_id')->get();
        foreach ($order_sizes as $key => $color_size) {
            $order_wise_data[$key]['color'] = $color_size->color->name;
            $order_wise_data[$key]['size'] = $color_size->size->name;
            $size_order_qty = $color_size->quantity;
            $order_wise_data[$key]['size_order_qty'] = $size_order_qty;

            $size_wise_cutting_qty = 0;
            $cutting_qty = 0;
            $print_received_qty = 0;
            $print_sent_qty = 0;
            $fabric_rejection = 0;
            $print_rejection = 0;

            $size_wise_bundles = BundleCard::where(['purchase_order_id' => $purchase_order_id, 'color_id' => $color_size->color_id, 'size_id' => $color_size->size_id, 'status' => 1])->get();

            $size_wise_cutting_qty = $size_wise_bundles->sum('quantity') - $size_wise_bundles->sum('total_rejection');
            foreach ($size_wise_bundles as $bundle) {
                if (isset($bundle->cutting_inventory) && $bundle->cutting_inventory->print_status == 1 && $bundle->status == 1) {
                    $print_received_qty += $bundle->quantity;
                    $print_rejection += $bundle->print_rejection;
                }
                if (isset($bundle->print_inventory)) {
                    $print_sent_qty += $bundle->quantity;
                    $fabric_rejection += $bundle->total_rejection;
                }
            }

            $order_wise_data[$key]['cutting_qty'] = $size_wise_cutting_qty;
            $cutting_wip = $size_wise_cutting_qty - $print_sent_qty;
            $order_wise_data[$key]['cutting_wip'] = $cutting_wip;
            $print_sent = $print_sent_qty - $fabric_rejection;
            $order_wise_data[$key]['print_sent_qty'] = $print_sent;

            $print_received = $print_received_qty - $print_rejection - $fabric_rejection;
            $order_wise_data[$key]['print_received_qty'] = $print_received;
            $order_wise_data[$key]['fabric_rejection'] = $fabric_rejection;
            $order_wise_data[$key]['print_rejection'] = $print_rejection;
            $total_rejection = $fabric_rejection + $print_rejection;
            $order_wise_data[$key]['total_rejection'] = $total_rejection;
            $print_wip_short = $print_sent - $print_received_qty - $print_rejection;
            $order_wise_data[$key]['print_wip_short'] = $print_wip_short;

            // total row of report
            $total_order_qty += $size_order_qty;
            $total_cutting_qty += $size_wise_cutting_qty;
            $total_wip_qty += $cutting_wip;
            $total_sent_qty += $print_sent_qty - $fabric_rejection;;
            $total_received_qty += $print_received;
            $total_print_wip += $print_wip_short;
            $total_fabric_rejection += $fabric_rejection;
            $total_print_rejection += $print_rejection;
            $total_rejections += $total_rejection;
        }

        $total_data['total_order_qty'] = $total_order_qty;
        $total_data['total_cutting_qty'] = $total_cutting_qty;
        $total_data['total_wip_qty'] = $total_wip_qty;
        $total_data['total_sent_qty'] = $total_sent_qty;
        $total_data['total_received_qty'] = $total_received_qty;
        $total_data['total_print_wip'] = $total_print_wip;
        $total_data['total_fabric_rejection'] = $total_fabric_rejection;
        $total_data['total_print_rejection'] = $total_print_rejection;
        $total_data['total_rejections'] = $total_rejections;

        $result['order_wise_data'] = $order_wise_data;
        $result['total_data'] = $total_data;

        return $result;
    }

    public function getOrderWisePrintReportDownload($type, $buyer_id, $purchase_order_id)
    {
        $data['result_report'] = $this->getOrderWisePrintReportData($buyer_id, $purchase_order_id);
        $order_query = PurchaseOrder::where('id', $purchase_order_id)->first();
        $data['po_no'] = $order_query->po_no ?? '';
        $data['order_style_no'] = $order_query->order->order_style_no ?? '';
        $data['buyer'] = $order_query->buyer->name ?? '';

        if ($type == 'pdf') {
            $pdf = \PDF::loadView('printembrdroplets::reports.downloads.pdf.order-wise-print-send-receive-report-download', $data, [], [
                'format' => 'A4-L'
            ]);
            return $pdf->download('order-wise-print-send-receive-report.pdf');
        } else {
            return \Excel::download(new PoWisePrintReportExport($data), 'order-wise-print-send-receive-report.xlsx');

            /*\Excel::create('Order Wise Print Send Receive Report', function ($excel) use ($data) {
                $excel->sheet('Order Wise Print Report Sheet', function ($sheet) use ($data) {
                    $sheet->loadView('printembrdroplets::reports.downloads.excel.order-wise-print-send-receive-report-download', $data);
                });
            })->export('xls');*/
        }
    }

    public function getDateWisePrintReport(Request $request)
    {
        $from_date = $request->from_date ?? date('Y-m-d');
        $to_date = $request->to_date ?? date('Y-m-d');
        if($request->from_date && $request->to_date) {
            $from_date = $request->from_date;
            $to_date = $request->to_date;

            $frmDate = Carbon::parse($from_date);
            $toDate = Carbon::parse($to_date);
            $diff = $frmDate->diffInDays($toDate);

            if ($diff > 30) {
                Session::flash('error', 'Please enter maximum one month date range');
                return redirect()->back();
            }
        }
        $reports = $this->getDateRangeWisePrintReportData($from_date, $to_date);
        //$sizeWiseReports = $this->getSizeWisePrintSentReceivedData($from_date, $to_date);

        return view('printembrdroplets::reports.date_wise_report_modify', [
            'reports' => $reports,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);
    }

    private function getSizeWisePrintSentReceivedData($from_date, $to_date)
    {
        $bundles = BundleCard::with([
            'buyer:id,name',
            'order.booking_no,order_style_no',
            'purchaseOrder.id,po_no',
            'color:id,name',
            'size:id,name'
        ])
        ->where('print_sent_date', '>=', $from_date)
        ->where('embroidary_received_date', '<=', $to_date)
        ->selectRaw(
            'sum(quantity) as print_sent_qty,
             sum(quantity) as print_print_qty,buyer_id,order_id,purchase_order_id,color_id,size_id')
        ->groupBy('buyer_id', 'order_id', 'purchase_order_id', 'color_id', 'size_id')
        ->get();

        // From Laravel 5.4 you can
        foreach ($bundles as $key => $value) {
            echo $value->qty."=><br/>";
            echo $value->buyer->name."=><br/>";
        }
    }

    private function getDateRangeWisePrintReportData($from_date, $to_date)
    {
        return DateWisePrintEmbrProductionReport::with('buyer:id,name', 'order:id,style_name', 'purchaseOrder:id,po_no,po_quantity', 'color:id,name', 'size:id,name', 'factory')
            ->whereDate('production_date', '>=', $from_date)
            ->whereDate('production_date', '<=', $to_date)
            ->selectRaw('buyer_id, order_id, purchase_order_id, color_id, size_id, factory_id,
                SUM(print_sent_qty) as print_sent_qty_sum,
                SUM(print_received_qty) as print_received_qty_sum,
                SUM(embroidery_sent_qty) as embroidery_sent_qty_sum,
                SUM(embroidery_received_qty) as embroidery_received_qty_sum
            ')
            ->groupBy('buyer_id', 'order_id', 'purchase_order_id', 'color_id', 'size_id', 'factory_id')
            ->orderBy('buyer_id', 'asc')
            ->orderBy('order_id', 'asc')
            ->orderBy('purchase_order_id', 'asc')
            ->orderBy('color_id', 'asc')
            ->orderBy('size_id', 'asc')
            ->get()
            ->filter(function ($item, $key) {
                return $item->print_sent_qty_sum > 0 || $item->print_received_qty_sum > 0 || $item->embroidery_sent_qty_sum > 0 || $item->embroidery_received_qty_sum > 0;
            });
    }

    /*public function getDateWisePrintReport()
    {
        $date = date('Y-m-d');
        return $this->getMothWisePrintReportView($date, $date);
    }*/

    public function getMothWisePrintReportPost(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date'
        ]);

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $frmDate = Carbon::parse($from_date);
        $toDate = Carbon::parse($to_date);
        $diff = $frmDate->diffInDays($toDate);

        if ($diff > 30) {
            Session::flash('error', 'Please enter maximum one month date range');
            return redirect('date-wise-print-send-report');
        }

        return $this->getMothWisePrintReportView($request->from_date, $request->to_date);
    }

    /*
    public function getMothWisePrintReportView($from_date, $to_date)
    {
        $data = $this->getDateWisePrintReportData($from_date, $to_date);

        return view('printembrdroplets::reports.date_wise_report', $data);
    }
    */

    public function getMothWisePrintReportView($from_date, $to_date)
    {
        $data = $this->getDateWisePrintReportData($from_date, $to_date);

        return view('printembrdroplets::reports.date_wise_report_modify', $data);
    }

    public function getDateWisePrintReportData($from_date, $to_date)
    {
        $date_wise_print_summary_report = [];
        $po_wise_print_report_query = DateWisePrintProductionReport::whereDate('print_date', '>=', $from_date)->whereDate('print_date', '<=', $to_date)->get();
        $j = 0;
        foreach ($po_wise_print_report_query as $floorKey => $group) {
            foreach ($group->print_details as $order) {
                $getPurchaseOrder = DateWisePrintProductionReport::getPurchaseOrder($order['purchase_order_id']);
                $getColor = DateWisePrintProductionReport::getColor($order['color_id']);
                $date_wise_print_summary_report[$j]['purchase_order_id'] = $getPurchaseOrder->id;
                $date_wise_print_summary_report[$j]['color_id'] = $getColor->id;
                $date_wise_print_summary_report[$j]['buyer_name'] = $getPurchaseOrder->buyer->name ?? 'Buyer';
                $date_wise_print_summary_report[$j]['order_style_no'] = $getPurchaseOrder->order->order_style_no ?? '';
                $date_wise_print_summary_report[$j]['color'] = $getColor->name ?? 'Color';
                $date_wise_print_summary_report[$j]['po_no'] = $getPurchaseOrder->po_no ?? '';
                $date_wise_print_summary_report[$j]['send_qty'] = $order['print_sent'] ?? 0;
                $j++;
            }
        }
        $factory_wise_report = DateWisePrintProductionReport::whereDate('print_date', '>=', $from_date)
            ->whereDate('print_date', '<=', $to_date)
            ->select('factory_id', 'total_print_sent')->get();
        $total_print_sent = DateWisePrintProductionReport::whereDate('print_date', '>=', $from_date)
            ->whereDate('print_date', '<=', $to_date)
            ->sum('total_print_sent');
        //add color and print count
        $colorCount = collect($date_wise_print_summary_report)->groupBy('color_id')->count();
        $poCount = collect($date_wise_print_summary_report)->groupBy('purchase_order_id')->count();

        $data = [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'date_wise_print_summary_report' => collect($date_wise_print_summary_report),
            'factory_wise_report' => $factory_wise_report,
            'total_print_sent' => $total_print_sent,
            'color_count' => $colorCount,
            'po_count' => $poCount
        ];
        return $data;
    }

    public function getDateWisePrintReportDownload(Request $request)
    {
        $type = $request->type;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $data['reports'] = $this->getDateRangeWisePrintReportData($from_date, $to_date);
        $data['po_count'] = $data['reports']->groupBy('purchase_order_id')->count();
        $data['color_count'] = $data['reports']->groupBy('color_id')->count();
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('printembrdroplets::reports.downloads.pdf.date-wise-print-send-receive-report-download',
                    $data, [], ['format' => 'A4-L']
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('date-wise-print-send-receive-report.pdf');
        } else {
            return \Excel::download(new DateWisePrintReportExport($data), 'date-wise-print-send-receive-report.xlsx');
        }
    }

    public function getPoWisePrintSummaryReport($from_date, $to_date)
    {
        $po_wise_print_summary_report = [];
        $po_wise_print_report_query = DateWisePrintProductionReport::whereDate('print_date', '>=', $from_date)->whereDate('print_date', '<=', $to_date)->get();
        $i = 0;
        foreach ($po_wise_print_report_query as $floorKey => $group) {
            foreach ($group->print_details as $order) {
                $getPurchaseOrder = DateWisePrintProductionReport::getPurchaseOrder($order['purchase_order_id']);
                $po_wise_print_summary_report[$i]['purchase_order_id'] = $getPurchaseOrder->id;
                $po_wise_print_summary_report[$i]['buyer_name'] = $getPurchaseOrder->buyer->name ?? 'Buyer';
                $po_wise_print_summary_report[$i]['order_style_no'] = $getPurchaseOrder->order->order_style_no ?? 'Style';
                $po_wise_print_summary_report[$i]['po_no'] = $getPurchaseOrder->po_no ?? '';
                $po_wise_print_summary_report[$i]['send_qty'] = $order['print_sent'] ?? 0;
                $i++;
            }
        }

        return collect($po_wise_print_summary_report);
    }

    public function getColorWisePrintSummaryReport($from_date, $to_date)
    {
        $color_wise_print_summary_report = [];
        $po_wise_print_report_query = DateWisePrintProductionReport::whereDate('print_date', '>=', $from_date)
            ->whereDate('print_date', '<=', $to_date)
            ->get();

        $j = 0;
        foreach ($po_wise_print_report_query as $floorKey => $group) {
            foreach ($group->print_details as $order) {
                $getPurchaseOrder = DateWisePrintProductionReport::getPurchaseOrder($order['purchase_order_id']);
                $getColor = DateWisePrintProductionReport::getColor($order['color_id']);
                $color_wise_print_summary_report[$j]['purchase_order_id'] = $getPurchaseOrder->id;
                $color_wise_print_summary_report[$j]['color_id'] = $getColor->id;
                $color_wise_print_summary_report[$j]['buyer_name'] = $getPurchaseOrder->buyer->name ?? 'Buyer';
                $color_wise_print_summary_report[$j]['order_style_no'] = $getPurchaseOrder->order->order_style_no ?? 'Style';
                $color_wise_print_summary_report[$j]['color'] = $getColor->name ?? 'Color';
                $color_wise_print_summary_report[$j]['po_no'] = $getPurchaseOrder->po_no ?? '';
                $color_wise_print_summary_report[$j]['send_qty'] = $order['print_sent'] ?? 0;
                $j++;
            }
        }

        return collect($color_wise_print_summary_report);
    }

    // FOR TNA
    public static function getOrderWiseActualPrintDateInfo($order_id)
    {
        // For Print Sent Date
        $bundleCardQuery = BundleCard::where(['order_id' => $order_id, 'status' => 1])->whereNotNull('print_sent_date');
        $actual_start = '';
        $actual_end = '';
        $duration = '';
        if($bundleCardQuery->count()) {
            $order_qty = Order::findOrFail($order_id)->total_quantity;
            $firstBundle = $bundleCardQuery->orderBy('print_sent_date', 'asc')->first();
            $actual_start = $firstBundle->print_sent_date;
            $bundleCardQueryClone = clone $bundleCardQuery;
            $printRecvQuery = $bundleCardQueryClone->whereNotNull('print_received_date');
            $printRecvQty = $printRecvQuery->sum('quantity') - $printRecvQuery->sum('total_rejection') - $printRecvQuery->sum('print_rejection');
            if ($printRecvQty >= $order_qty) {
                $actual_end = $printRecvQuery->orderBy('print_received_date', 'desc')->first()->print_received_date;
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
