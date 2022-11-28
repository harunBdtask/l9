<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Controllers;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\InputClosingReportExport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use Carbon\Carbon;

class InputClosingAndLineController extends Controller
{

    public function getInputClosingReport()
    {
        return view('inputdroplets::reports.input-closing');
    }

    public function getInputClosingReportView($order_id, $purchase_order_id, $color_id)
    {
        $result = [];
        $total_size_order = 0;
        $total_size_cutting = 0;
        $total_rejection = 0;
        $total_today_input = 0;
        $total_total_input = 0;
        $total_today_output = 0;
        $total_total_output = 0;
        $total_wip = 0;
        $total_in_line_wip = 0;
        $report_size_wise = [];
        $total_report = [];

        if ($purchase_order_id == 'all_po') {
            $order_sizes = PurchaseOrderDetail::withoutGlobalScope('factoryId')
                ->with('color', 'size')
                ->join('purchase_orders', 'purchase_orders.id', 'purchase_order_details.purchase_order_id')
                ->where([
                    'purchase_orders.order_id' => $order_id,
                    'purchase_order_details.color_id' => $color_id
                ])
                ->selectRaw('purchase_order_details.color_id as color_id ,purchase_order_details.size_id as size_id, SUM(purchase_order_details.quantity) as quantity')
                ->groupBy('purchase_order_details.color_id', 'purchase_order_details.size_id')
                ->get()
                ->filter(function($item, $key) {
                    return $item->quantity > 0;
                });
        } else {
            $order_sizes = PurchaseOrderDetail::where([
                'purchase_order_id' => $purchase_order_id,
                'color_id' => $color_id
            ])->get()
                ->filter(function($item, $key) {
                return $item->quantity > 0;
            });
        }

        foreach ($order_sizes as $key => $size) {
            $report_size_wise[$key]['size'] = $size->size->name;
            $report_size_wise[$key]['size_order_qty'] = $size->quantity;

            $bundle_cards = BundleCard::where([
                'color_id' => $size->color_id,
                'size_id' => $size->size_id,
                'status' => 1
            ])
                ->when($purchase_order_id != 'all_po', function ($query) use($purchase_order_id) {
                    $query->where('purchase_order_id', $purchase_order_id);
                })
                ->when($purchase_order_id == 'all_po', function ($query) use($order_id) {
                    $query->where('order_id', $order_id);
                })
                ->with('cutting_inventory', 'sewingoutput')
                ->get();

            $size_cutting_qty = 0;
            $today_input = 0;
            $total_input = 0;
            $today_output = 0;
            $total_output = 0;
            $rejection = 0;

            foreach ($bundle_cards as $bundle) {
                // size wise cutting qty
                if ($bundle->size_id == $size->size_id) {
                    $size_cutting_qty += $bundle->quantity - $bundle->total_rejection;
                    $rejection += $bundle->total_rejection + $bundle->print_rejection + $bundle->embroidary_rejection + $bundle->sewing_rejection;

                    // today & total input to line 
                    if (isset($bundle->cutting_inventory->cutting_inventory_challan->line_id)) {
                        if ($bundle->cutting_inventory->cutting_inventory_challan->input_date == date('Y-m-d')) {
                            $today_input += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection - $bundle->embroidary_rejection;
                        }
                        $total_input += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection - $bundle->embroidary_rejection;
                    }
                    // today & total sewing output 
                    if (isset($bundle->sewingoutput)) {
                        if (date('Y-m-d', strtotime($bundle->sewingoutput->created_at)) == date('Y-m-d')) {
                            $today_output += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection - $bundle->embroidary_rejection - $bundle->sewing_rejection;
                        }
                        $total_output += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection - $bundle->embroidary_rejection - $bundle->sewing_rejection;
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
            $total_size_order += $size->quantity;
            $total_size_cutting += $size_cutting_qty;
            $total_rejection += $rejection;
            $total_today_input += $today_input;
            $total_total_input += $total_input;
            $total_today_output += $today_output;
            $total_total_output += $total_output;
            $total_wip += $wip;
            $total_in_line_wip += $in_line_wip;
        }

        $total_report['total_size_order'] = $total_size_order;
        $total_report['total_size_cutting'] = $total_size_cutting;
        $total_report['total_wip'] = $total_wip;
        $total_report['total_today_input'] = $total_today_input;
        $total_report['total_total_input'] = $total_total_input;
        $total_report['total_today_output'] = $total_today_output;
        $total_report['total_total_output'] = $total_total_output;
        $total_report['total_rejection'] = $total_rejection;
        $total_report['total_in_line_wip'] = $total_in_line_wip;

        $result['report_size_wise'] = $report_size_wise;
        $result['total_report'] = $total_report;

        return $result;
    }

    public function getInputClosingReportDownload($type, $buyer_id, $order_id, $purchase_order_id, $color_id)
    {
        $data = $this->getInputClosingReportView($order_id, $purchase_order_id, $color_id);
        if ($purchase_order_id != 'all_po') {
            $porder = PurchaseOrder::findOrFail($purchase_order_id)->po_no ?? '';
        }
        $report_head = [
            'buyer' => Buyer::findOrFail($buyer_id)->name ?? '',
            'style' => Order::findOrFail($order_id)->order_style_no ?? '',
            'order_no' => $porder ?? 'All PO',
            'color' => Color::where('id', $color_id)->first()->name ?? '',
        ];
        if ($type == 'pdf') {
            $pdf = \PDF::loadView('inputdroplets::reports.downloads.pdf.get-input-closing-report-download', $data, $report_head)->setPaper('a4', 'landscape');
            return $pdf->download('input-closing-report.pdf');
        } else {
            return \Excel::download(new InputClosingReportExport($data, $report_head), 'input-closing-report.xlsx');
        }
    }

    public function getLineWiseReport()
    {

        $line_wise_report = CuttingInventoryChallan::with(
            'buyer', 'order', 'line', 'cutting_inventory',
            'cutting_inventory.bundlecard')
            ->orderBy('line_id', 'DESC')
            ->limit(200)
            ->get()
            ->groupBy('order_id')
            ->map(function ($items) {
                $unique_order = $items->first();
                $order_output = $items->map(function ($input_challan) {

                    $inputDate = $input_challan->updated_at->toDateString();
                    $today = Carbon::today()->toDateString();

                    $today_output = $input_challan->sewing_ouput->reject(function ($sw) use ($today) {
                        return ($today != $sw->updated_at->toDateString());
                    })->sum('bundlecard.quantity');

                    return [
                        'today_input' => ($inputDate == $today) ? $input_challan->cutting_inventory->sum('bundlecard.quantity') : 0,

                        'total_input' => $input_challan->cutting_inventory->sum('bundlecard.quantity'),
                        'today_output' => $today_output,
                        'total_output' => $input_challan->sewing_ouput->sum('bundlecard.quantity'),
                        'total_rejection' => $input_challan->cutting_inventory->sum('bundlecard.total_rejection') + $input_challan->cutting_inventory->sum('bundlecard.print_rejection') + $input_challan->cutting_inventory->sum('bundlecard.sewing_rejection'),
                    ];
                });

                $reject_ratio = 0;
                if ($order_output->sum('total_rejection') > 0 && $order_output->sum('total_output') > 0) {
                    $reject_ratio = ($order_output->sum('total_rejection') * 100) / $order_output->sum('total_output');
                }

                $line_wip = $order_output->sum('total_input') - $order_output->sum('total_output') - $order_output->sum('total_rejection');

                $wip = 0;
                if ($line_wip > 0 && $order_output->sum('total_input') > 0) {
                    $wip = number_format(($line_wip / $order_output->sum('total_input')) * 100, 2);
                }

                return [
                    'floor' => $unique_order->line->floor->floor_no ?? '',
                    'line' => $unique_order->line->line_no ?? '',
                    'buyer' => $unique_order->order->buyer->name ?? '',
                    'order' => $unique_order->order->order_no ?? '',
                    'style' => $unique_order->order->style->name ?? '',
                    'today_input' => $order_output->sum('today_input'),
                    'total_input' => (($order_output->sum('total_input') - $order_output->sum('total_rejection')) > 0) ? $order_output->sum('total_input') - $order_output->sum('total_rejection') : 0,
                    'today_output' => $order_output->sum('today_output'),
                    'total_output' => (($order_output->sum('total_output') - $order_output->sum('total_rejection')) > 0) ? $order_output->sum('total_output') - $order_output->sum('total_rejection') : 0,
                    'rejection' => $order_output->sum('total_rejection'),
                    'reject_ratio' => number_format($reject_ratio, 2),
                    'line_wip' => $line_wip,
                    'wip' => $wip,
                ];
            });

        return view('reports.input-module.line-wise-report')
            ->with('line_wise_report', $line_wise_report);
    }

    public function getLineWiseReportData()
    {
        $line_wise_report = CuttingInventoryChallan::with(
            'buyer', 'order', 'line', 'cutting_inventory',
            'cutting_inventory.bundlecard')
            ->get()
            ->groupBy('order_id')
            ->map(function ($items) {
                $unique_order = $items->first();
                $order_output = $items->map(function ($input_challan) {

                    $inputDate = $input_challan->updated_at->toDateString();
                    $today = Carbon::today()->toDateString();

                    $today_output = $input_challan->sewing_ouput->reject(function ($sw) use ($today) {
                        return ($today != $sw->updated_at->toDateString());
                    })->sum('bundlecard.quantity');

                    return [
                        'today_input' => ($inputDate == $today) ? $input_challan->cutting_inventory->sum('bundlecard.quantity') : 0,

                        'total_input' => $input_challan->cutting_inventory->sum('bundlecard.quantity'),
                        'today_output' => $today_output,
                        'total_output' => $input_challan->sewing_ouput->sum('bundlecard.quantity'),
                        'total_rejection' => $input_challan->cutting_inventory->sum('bundlecard.total_rejection') + $input_challan->cutting_inventory->sum('bundlecard.print_rejection') + $input_challan->cutting_inventory->sum('bundlecard.sewing_rejection'),
                    ];
                });

                $reject_ratio = 0;
                if ($order_output->sum('total_rejection') > 0 && $order_output->sum('total_output') > 0) {
                    $reject_ratio = ($order_output->sum('total_rejection') * 100) / $order_output->sum('total_output');
                }

                $line_wip = $order_output->sum('total_input') - $order_output->sum('total_output') - $order_output->sum('total_rejection');

                $wip = 0;
                if ($line_wip > 0 && $order_output->sum('total_input') > 0) {
                    $wip = number_format(($line_wip / $order_output->sum('total_input')) * 100, 2);
                }

                return [
                    'floor' => $unique_order->line->floor->floor_no ?? '',
                    'line' => $unique_order->line->line_no ?? '',
                    'buyer' => $unique_order->order->buyer->name ?? '',
                    'order' => $unique_order->order->order_no ?? '',
                    'style' => $unique_order->order->style->name ?? '',
                    'today_input' => $order_output->sum('today_input'),
                    'total_input' => (($order_output->sum('total_input') - $order_output->sum('total_rejection')) > 0) ? $order_output->sum('total_input') - $order_output->sum('total_rejection') : 0,
                    'today_output' => $order_output->sum('today_output'),
                    'total_output' => (($order_output->sum('total_output') - $order_output->sum('total_rejection')) > 0) ? $order_output->sum('total_output') - $order_output->sum('total_rejection') : 0,
                    'rejection' => $order_output->sum('total_rejection'),
                    'reject_ratio' => number_format($reject_ratio, 2),
                    'line_wip' => $line_wip,
                    'wip' => $wip,
                ];
            });
        return $line_wise_report;
    }

    public function getLineWiseReportDownload($type)
    {
        $data['line_wise_report'] = $this->getLineWiseReportData();
        if ($type == 'pdf') {
            $pdf = \PDF::loadView('reports.downloads.input-module.pdf.line-wise-input-report-download', $data, [], [
                'format' => 'A4-L'
            ]);
            return $pdf->download('line-wise-input-report.pdf');
        } else {
            \Excel::create('Line Wise Input Report', function ($excel) use ($data) {
                $excel->sheet('Line Wise Input', function ($sheet) use ($data) {
                    $sheet->loadView('reports.downloads.input-module.excels.line-wise-input-report-download', $data);
                });
            })->export('xls');
        }
    }


}
