<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\DateWiseFinishingReportExport;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\POWiseGetUpFinishingReportExport;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\POWiseGetUpFinishingV2ReportExport;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\SizeWiseFinishingReportExport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\Finishing;

class ColorAndSizeReportController extends Controller
{
    public function getFinishingReceivedReportForm()
    {
        $buyers = Buyer::pluck('name', 'id')->all();
        return view('finishingdroplets::reports.finising_received_report', [
            'buyers' => $buyers
        ]);
    }

    public function finishingReportColorWise($purchase_order_id)
    {
        $result = [];
        $total_order_qty = 0;
        $total_cutting_qty = 0;
        $total_total_input = 0;
        $total_total_output = 0;
        $total_in_line_wip = 0;
        $total_finished_qty = 0;
        $report_size_wise = [];
        $total_report = [];

        $order_sizes = PurchaseOrderDetail::where('purchase_order_id', $purchase_order_id)->get();
        foreach ($order_sizes as $key => $size) {
            $report_size_wise[$key]['color'] = $size->color->name;
            $report_size_wise[$key]['size'] = $size->size->name;
            $report_size_wise[$key]['size_order_qty'] = $size->quantity;

            $finished_qty = Finishing::where(['color_id' => $size->color_id, 'purchase_order_id' => $purchase_order_id, 'size_id' => $size->size_id])->sum('quantity');

            $report_size_wise[$key]['finished_qty'] = $finished_qty;
            $bundle_cards = BundleCard::where(['color_id' => $size->color_id, 'purchase_order_id' => $purchase_order_id, 'size_id' => $size->size_id, 'status' => 1])
                ->with('cutting_inventory', 'cutting_inventory.cutting_inventory_challan', 'sewingoutput')
                ->get();

            $size_cutting_qty = 0;
            $total_input = 0;
            $total_output = 0;
            $rejection = 0;
            foreach ($bundle_cards as $bundle) {
                $size_cutting_qty += $bundle->quantity - $bundle->total_rejection;
                // total input to line
                if (isset($bundle->cutting_inventory->cutting_inventory_challan->line_id)) {
                    $total_input += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection;
                }
                if (isset($bundle->sewingoutput)) {
                    $total_output += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection - $bundle->sewing_rejection;
                }
            }


            $report_size_wise[$key]['size_cutting_qty'] = $size_cutting_qty;
            $report_size_wise[$key]['total_input'] = $total_input;
            $report_size_wise[$key]['total_output'] = $total_output;
            $in_line_wip = (($total_input - $finished_qty) > 0) ? ($total_input - $finished_qty) : 0;
            $report_size_wise[$key]['in_line_wip'] = $in_line_wip;

            // total row of report
            $total_order_qty += $size->quantity;
            $total_cutting_qty += $size_cutting_qty;
            $total_total_input += $total_input;
            $total_total_output += $total_output;
            $total_in_line_wip += $in_line_wip;
            $total_finished_qty += $finished_qty;
        }

        $total_report['total_order_qty'] = $total_order_qty;
        $total_report['total_cutting_qty'] = $total_cutting_qty;
        $total_report['total_total_input'] = $total_total_input;
        $total_report['total_total_output'] = $total_total_output;
        $total_report['total_finished_qty'] = $total_finished_qty;
        $total_report['total_in_line_wip'] = $total_in_line_wip;

        $result['report_size_wise'] = $report_size_wise;
        $result['total_report'] = $total_report;

        return $result;
    }

    public function finishingReceivedReportDownload($type, $purchase_order_id)
    {
        $data = $this->finishingReportColorWise($purchase_order_id);
        $order_query = PurchaseOrder::where('id', $purchase_order_id)->first();
        $report_head = [
            'buyer' => $order_query->buyer->name ?? '',
            'order_style_no' => $order_query->order->order_style_no ?? '',
            'po_no' => $order_query->po_no ?? '',
        ];
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('finishingdroplets::reports.downloads.pdf.finishing-report-download',
                    $data, $report_head, [], ['format' => 'A4-L']
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('order-wise-finishing-report.pdf');
        } else {
            return \Excel::download(new POWiseGetUpFinishingReportExport($data, $report_head), 'po-wise-finishing-report.xlsx');

            /*\Excel::create('Order Wise Get Up Finished Report', function ($excel) use ($data, $report_head) {
                $excel->sheet('Order Wise Finished Report', function ($sheet) use ($data, $report_head) {
                    $sheet->loadView('finishingdroplets::reports.downloads.excels.finishing-report-download', $data, $report_head);
                });
            })->export('xls');*/
        }
    }

    // color wise report
    public function orderWiseFinishingReportForm()
    {
        $buyers = Buyer::pluck('name', 'id')->all();
        return view('finishingdroplets::reports.order_wise_finising_report', [
            'buyers' => $buyers
        ]);
    }

    public function orderWiseFinishingReport($purchase_order_id)
    {
        $result = [];
        $total_order_qty = 0;
        $total_cutting_qty = 0;
        $total_total_input = 0;
        $total_total_output = 0;
        $total_wip = 0;
        $total_in_line_wip = 0;
        $total_finished_qty = 0;
        $total_rejection = 0;
        $total_cutt_order = 0;
        $total_order_to_input = 0;
        $total_output = 0;
        $total_goq = 0;
        $report_color_wise = [];
        $total_report = [];

        $order_sizes = PurchaseOrderDetail::where('purchase_order_id', $purchase_order_id)->get();
        foreach ($order_sizes as $key => $size) {
            $report_color_wise[$key]['color'] = $size->color->name;
            $report_color_wise[$key]['size'] = $size->size->name;
            $report_color_wise[$key]['size_order_qty'] = $size->quantity;

            $finished_qty = Finishing::where(['color_id' => $size->color_id, 'purchase_order_id' => $purchase_order_id, 'size_id' => $size->size_id])->sum('quantity');
            $report_color_wise[$key]['finished_qty'] = $finished_qty;

            $bundle_cards = BundleCard::where(['color_id' => $size->color_id, 'purchase_order_id' => $purchase_order_id, 'size_id' => $size->size_id, 'status' => 1])
                ->with('cutting_inventory', 'cutting_inventory.cutting_inventory_challan', 'sewingoutput')
                ->get();

            $size_cutting_qty = 0;
            $total_input = 0;
            $rejection = 0;
            $output = 0;
            $goq = 0;
            $gpercent = 0;
            foreach ($bundle_cards as $bundle) {
                $size_cutting_qty += $bundle->quantity;
                $rejection = $bundle->total_rejection + $bundle->print_rejection + $bundle->sewing_rejection;
                // total input to line
                if (isset($bundle->cutting_inventory->cutting_inventory_challan->line_id)) {
                    $total_input += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection;
                }
                // total output
                if (isset($bundle->sewingoutput)) {
                    $output += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection - $bundle->sewing_rejection;
                }
            }

            $report_color_wise[$key]['size_cutting_qty'] = $size_cutting_qty;
            $report_color_wise[$key]['total_input'] = $total_input;
            $report_color_wise[$key]['total_output'] = $output;
            $in_line_wip = $total_input - $finished_qty;
            $report_color_wise[$key]['in_line_wip'] = $in_line_wip;
            $report_color_wise[$key]['rejection'] = $rejection;
            $wip = $size_cutting_qty - $total_input;
            $report_color_wise[$key]['wip'] = $wip;

            $total_cutt_order = ($size_cutting_qty > 0 && $size->quantity > 0) ? round(($size_cutting_qty / $size->quantity) * 100, 2) : 0;
            $report_color_wise[$key]['total_cutt_order'] = $total_cutt_order;

            $order_to_input = ($total_input > 0 && $size->quantity > 0) ? round(($total_input / $size->quantity) * 100, 2) : 0;
            $report_color_wise[$key]['order_to_input'] = $order_to_input;
            $ratio = ($output > 0 && $size_cutting_qty > 0) ? number_format(($output / $size_cutting_qty) * 100, 2) : 0;
            $report_color_wise[$key]['ratio'] = $ratio;
            $report_color_wise[$key]['goq'] = $goq;
            $report_color_wise[$key]['balance'] = $goq - $size->quantity;
            $report_color_wise[$key]['gpercent'] = $gpercent;


            // total row of report
            $total_order_qty += $size->quantity;
            $total_cutting_qty += $size_cutting_qty;
            $total_total_input += $total_input;
            $total_wip += $wip;
            $total_in_line_wip += $in_line_wip;
            $total_finished_qty += $finished_qty;
            $total_rejection += $rejection;
            $total_cutt_order += $total_cutt_order;
            $total_order_to_input += $order_to_input;
            $total_goq += $goq;
            $total_total_output += $output;

        }

        $total_report['total_total_input'] = $total_total_input;
        $total_report['total_total_output'] = $total_total_output;
        $total_report['total_cutting_qty'] = $total_cutting_qty;
        $total_report['total_finished_qty'] = $total_finished_qty;
        $total_report['total_order_qty'] = $total_order_qty;
        $total_report['total_wip'] = $total_wip;
        $total_report['total_in_line_wip'] = $total_in_line_wip;
        $total_report['total_rejection'] = $total_rejection;
        $total_report['total_cutt_order'] = $total_cutt_order;
        $total_report['total_order_to_input'] = $total_order_to_input;

        $result['report_color_wise'] = $report_color_wise;
        $result['total_report'] = $total_report;

        return $result;
    }

    public function colorWiseFinishingReportDownload($type, $purchase_order_id)
    {
        $data = $this->orderWiseFinishingReport($purchase_order_id);
        $order_query = PurchaseOrder::where('id', $purchase_order_id)->first();
        $report_head = [
            'buyer' => $order_query->buyer->name ?? '',
            'order_style_no' => $order_query->order->order_style_no ?? '',
            'po_no' => $order_query->po_no ?? '',
        ];
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('finishingdroplets::reports.downloads.pdf.color-wise-finishing-report-download',
                    $data, $report_head, [], ['format' => 'A4-L']
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('color-wise-finishing-report.pdf');
        } else {
            return \Excel::download(new POWiseGetUpFinishingV2ReportExport($data, $report_head), 'po-wise-finishing-report-v2.xlsx');

            /*\Excel::create('Order Wise Get Up Finished Report', function ($excel) use ($data,$report_head) {
                $excel->sheet('Order Wise Finished Report', function ($sheet) use ($data,$report_head) {
                    $sheet->loadView('finishingdroplets::reports.downloads.excels.color-wise-finishing-report-download', $data,$report_head);
                });
            })->export('xls');*/
        }
    }

    // date ise report view
    public function dateWiseFinishingReport()
    {
        $date = date('Y-m-d');
        $data = $this->getDateWiseFinishingReport($date, $date);
        return view('finishingdroplets::reports.date-wise-finishing-report', $data);
    }

    public function dateWiseFinishingReportPostAction(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date'
            //'to_date' => 'required|date|after_or_equal:from_date'
        ]);
        $data = $this->getDateWiseFinishingReport($request->from_date, $request->to_date);
        return view('finishingdroplets::reports.date-wise-finishing-report', $data);
    }

    public function getDateWiseFinishingReport($from_date, $to_date): array
    {
        $date_wise_report = Finishing::with('buyer', 'order')
            ->whereDate('created_at', '>=', $from_date)
            ->whereDate('created_at', '<=', $to_date)
            ->get();

        // buyer wise report
        $buyer_wise_report = [];
        foreach ($date_wise_report->groupBy('buyer_id') as $key => $buyer_wise) {
            $buyer_wise_report[$key]['buyer'] = $buyer_wise->first()->buyer->name;
            $buyer_wise_report[$key]['finished_qty'] = $buyer_wise->sum('quantity');
        }

        // order wise report
        $order_wise_report = [];
        foreach ($date_wise_report->groupBy('purchase_order_id') as $key => $order_wise) {
            $buyer_order = $order_wise->first();
            $order_wise_report[$key]['buyer'] = $buyer_order->buyer->name;
            $order_wise_report[$key]['style'] = $buyer_order->order->order_style_no;
            $order_wise_report[$key]['order'] = $buyer_order->purchaseOrder->po_no;
            $order_wise_report[$key]['order_finished_qty'] = $order_wise->sum('quantity');
        }

        // color wise report
        $color_wise_report = [];
        foreach ($date_wise_report->groupBy('color_id') as $key => $color_wise) {
            $buyer_order_color = $color_wise->first();
            $color_wise_report[$key]['buyer'] = $buyer_order_color->buyer->name;
            $color_wise_report[$key]['style'] = $buyer_order_color->order->order_style_no;
            $color_wise_report[$key]['order'] = $buyer_order_color->purchaseOrder->po_no;
            $color_wise_report[$key]['color'] = $buyer_order_color->color->name;
            $color_wise_report[$key]['color_finished_qty'] = $color_wise->sum('quantity');
        }

        // size wise report
        $size_wise_report = [];
        foreach ($date_wise_report->groupBy('size_id') as $key => $size_wise) {
            $buyer_order_color_size = $color_wise->first();
            $size_wise_report[$key]['buyer'] = $buyer_order_color_size->buyer->name;
            $size_wise_report[$key]['style'] = $buyer_order_color_size->order->order_style_no;
            $size_wise_report[$key]['order'] = $buyer_order_color_size->purchaseOrder->po_no;
            $size_wise_report[$key]['color'] = $buyer_order_color_size->color->name;
            $size_wise_report[$key]['size'] = $buyer_order_color_size->size->name;
            $size_wise_report[$key]['size_finished_qty'] = $size_wise->sum('quantity');
        }

        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['buyer_wise_report'] = $buyer_wise_report;
        $data['order_wise_report'] = $order_wise_report;
        $data['color_wise_report'] = $color_wise_report;
        $data['size_wise_report'] = $size_wise_report;
        $data['buyer_wise_count'] = collect($buyer_wise_report)->count();
        $data['order_wise_count'] = collect($order_wise_report)->count();
        $data['color_wise_count'] = collect($color_wise_report)->count();
        $data['size_wise_count'] = collect($size_wise_report)->count();
        return $data;
    }

    public function dateWiseFinishingReportDownload($type, $from_date, $to_date)
    {
        $data = $this->getDateWiseFinishingReport($from_date, $to_date);
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('finishingdroplets::reports.downloads.pdf.date-wise-finishing-report-download',
                    $data, [], ['format' => 'A4-L']
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('date-wise-finishing-report.pdf');
        } else {
            return \Excel::download(new DateWiseFinishingReportExport($data), 'date-wise-finishing-report.xlsx');
        }
    }

    // color wise finishing report
    public function colorWiseFinishingReport()
    {
        $buyers = Buyer::pluck('name', 'id')->all();
        return view('finishingdroplets::reports.color-wise-finising-report')->with('buyers', $buyers);
    }

    public function colorWiseFinishingReportAction($purchase_order_id, $color_id)
    {
        $result = [];
        $total_order_qty = 0;
        $total_cutting_qty = 0;
        $total_total_input = 0;
        $total_wip = 0;
        $total_in_line_wip = 0;
        $total_finished_qty = 0;
        $total_rejection = 0;
        $total_cutt_order = 0;
        $total_order_to_input = 0;
        $total_output = 0;
        $total_goq = 0;
        $report_size_wise = [];
        $total_report = [];

        $order_sizes = PurchaseOrderDetail::with('color', 'size')
            ->where(['purchase_order_id' => $purchase_order_id, 'color_id' => $color_id])
            ->get();

        foreach ($order_sizes as $key => $size) {
            $report_size_wise[$key]['size'] = $size->size->name;
            $report_size_wise[$key]['size_order_qty'] = $size->quantity;

            $finished_qty = Finishing::where(['color_id' => $size->color_id, 'purchase_order_id' => $purchase_order_id, 'size_id' => $size->size_id])->sum('quantity');
            $report_size_wise[$key]['finished_qty'] = $finished_qty;

            $bundle_cards = BundleCard::where(['color_id' => $color_id, 'purchase_order_id' => $purchase_order_id, 'size_id' => $size->size_id, 'status' => 1])
                ->with('cutting_inventory', 'cutting_inventory.cutting_inventory_challan')
                ->get();

            $size_cutting_qty = 0;
            $total_input = 0;
            $rejection = 0;
            $output = 0;
            $goq = 0;
            $gpercent = 0;

            foreach ($bundle_cards as $bundle) {
                // size wise cutting qty
                $size_cutting_qty += $bundle->quantity - $bundle->total_rejection;
                $rejection = $bundle->total_rejection + $bundle->print_rejection + $bundle->sewing_rejection;
                // total input to line
                if (isset($bundle->cutting_inventory->cutting_inventory_challan->line_id)) {
                    $total_input += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection;
                }
                // total output
                if (isset($bundle->sewingoutput)) {
                    $output += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection - $bundle->sewing_rejection;
                }
            }

            $report_size_wise[$key]['size_cutting_qty'] = $size_cutting_qty;
            $report_size_wise[$key]['total_input'] = $total_input;
            $in_line_wip = $total_input - $finished_qty;
            $report_size_wise[$key]['in_line_wip'] = $in_line_wip;
            $report_size_wise[$key]['rejection'] = $rejection;
            $wip = $size_cutting_qty - $total_input;
            $report_size_wise[$key]['wip'] = $wip;
            $report_size_wise[$key]['total_cutt_order'] = $total_cutt_order;

            $order_to_input = ($total_input > 0 && $size->quantity > 0) ? round(($total_input / $size->quantity) * 100, 2) : 0;
            $report_size_wise[$key]['order_to_input'] = $order_to_input;

            //$report_size_wise[$key]['output'] = $output;
            $ratio = ($output > 0 && $size_cutting_qty > 0) ? number_format(($output / $size_cutting_qty) * 100, 2) : 0;
            $report_size_wise[$key]['ratio'] = $ratio;
            $report_size_wise[$key]['goq'] = $goq;
            $report_size_wise[$key]['balance'] = $goq - $size->quantity;
            $report_size_wise[$key]['gpercent'] = $gpercent;


            // total row of report
            $total_order_qty += $size->quantity;
            $total_cutting_qty += $size_cutting_qty;
            $total_total_input += $total_input;
            $total_wip += $wip;
            $total_in_line_wip += $in_line_wip;
            $total_finished_qty += $finished_qty;
            $total_rejection += $rejection;
            $total_cutt_order += $total_cutt_order;
            $total_order_to_input += $order_to_input;
            $total_goq += $goq;
            // $total_output += $output;

        }

        $total_report['total_total_input'] = $total_total_input;
        $total_report['total_cutting_qty'] = $total_cutting_qty;
        $total_report['total_finished_qty'] = $total_finished_qty;
        $total_report['total_order_qty'] = $total_order_qty;
        $total_report['total_wip'] = $total_wip;
        $total_report['total_in_line_wip'] = $total_in_line_wip;
        $total_report['total_rejection'] = $total_rejection;
        $total_report['total_cutt_order'] = $total_cutt_order;
        $total_report['total_order_to_input'] = $total_order_to_input;

        $result['report_size_wise'] = $report_size_wise;
        $result['total_report'] = $total_report;

        return $result;
    }

    public function sizeWiseFinishingReportDownload($type, $purchase_order_id, $color_id)
    {
        $data = $this->colorWiseFinishingReportAction($purchase_order_id, $color_id);
        $order_query = PurchaseOrder::where('id', $purchase_order_id)->first();
        $report_head = [
            'buyer' => $order_query->buyer->name ?? '',
            'order_style_no' => $order_query->order->order_style_no ?? '',
            'po_no' => $order_query->po_no ?? '',
            'color' => Color::where('id', $color_id)->first()->name ?? '',
        ];
        if ($type == 'pdf') {
            $pdf = \PDF::loadView('finishingdroplets::reports.downloads.pdf.size-wise-finishing-report-download', $data, $report_head, [], [
                'format' => 'A4-L'
            ]);
            return $pdf->download('size-wise-finishing-report.pdf');
        } else {
            return \Excel::download(new SizeWiseFinishingReportExport($data, $report_head), 'size-wise-finishing-report.xlsx');

            /*\Excel::create('Size Wise Get Up Finished Report', function ($excel) use ($data,$report_head) {
                $excel->sheet('Size Wise Finished Report', function ($sheet) use ($data,$report_head) {
                    $sheet->loadView('finishingdroplets::reports.downloads.excels.size-wise-finishing-report-download', $data,$report_head);
                });
            })->export('xls');*/
        }
    }

}
