<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use PDF;
use PhpOffice\PhpSpreadsheet\Exception;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\Sewingdroplets\Exports\AllOrdersSewingOutputReportExport;
use SkylarkSoft\GoRMG\Sewingdroplets\Exports\BuyerWiseSewingOutputReportExport;
use SkylarkSoft\GoRMG\Sewingdroplets\Exports\PoWiseSewingReportExport;
use SkylarkSoft\GoRMG\Sewingdroplets\Exports\StyleWiseSewingReportExport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class OrderColorSizeController extends Controller
{
    public function orderWiseReport(Request $request)
    {
        $order_id = $request->get('order_id');
        $orders = $order_id ? Order::query()->where('id', $order_id)->pluck('style_name', 'id') : [];
        $buyers = Buyer::query()->pluck('short_name', 'id')->prepend('Select Buyer', '')->all();
        $sustainable_materials = collect(Order::SUSTAINABLE_MATERIAL)->prepend('Select Sustainable Material', 0);
        $order_wise_report = $this->orderWiseReportData($request);

        return view('sewingdroplets::reports.all_orders_sewing_summary_report', [
            'order_wise_report' => $order_wise_report,
            'orders' => $orders,
            'buyers' => $buyers,
            'sustainable_materials' => $sustainable_materials,
            'download' => 1
        ]);
    }

    public function orderWiseReportData($request): LengthAwarePaginator
    {
        $year = $request->get('year');
        $month = $request->get('month');
        $order_id = $request->get('order_id');
        $buyer_id = $request->get('buyer_id');
        $sustainable_material = $request->get('sustainable_material');

        return TotalProductionReport::query()
            ->with([
                'buyer:id,name',
                'order:id,style_name,sustainable_material',
                'purchaseOrder:id,po_no,po_quantity,created_at',
                'color:id,name',
            ])
            ->when($month || $year, function ($query) use ($month, $year) {
                $query->whereHas('purchaseOrder', function ($query) use ($month, $year) {
                    $query->when($year, Filter::applyYearFilter('created_at', years()[$year] ?? ''));
                    $query->when($month, Filter::applyMonthFilter('created_at', $month));
                });
            })
            ->when($sustainable_material, function ($query) use ($sustainable_material) {
                $query->whereHas('order', function ($q) use ($sustainable_material) {
                    $q->where('sustainable_material', $sustainable_material);
                });
            })
            ->when($order_id, Filter::applyFilter('order_id', $order_id))
            ->when($buyer_id, Filter::applyFilter('buyer_id', $buyer_id))
            ->orderBy('buyer_id', 'desc')
            ->paginate(PAGINATION);
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function allOrdersSewingOutputReportDownload(Request $request): BinaryFileResponse
    {
        $type = $request->get('type');
        $page = $request->get('page');
        $order_id = $request->get('order_id_id') ?? null;

        if ($type == 'pdf') {
            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
            $download = 0;
            $order_wise_report = $this->orderWiseReportData($request);
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('sewingdroplets::reports.downloads.order-wise-report-pdf',
                    compact('order_wise_report', 'download')
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('all-orders-sewing-output-report.pdf');
        } else {
            return \Excel::download(
                new AllOrdersSewingOutputReportExport($order_id),
                'all-orders-sewing-output-report.xlsx'
            );
        }
    }

    public function getBuyerWiseReport()
    {
        return view('sewingdroplets::reports.buyer_wise_report');
    }

    public function getBuyerWiseReportSewingOutput(Request $request)
    {
        $buyer_id = $request->buyer_id;
        $order_id = $request->order_id;
        $total_production_report_query = TotalProductionReport::with('buyer', 'order', 'purchaseOrder')
            ->where('order_id', $order_id)
            ->orderBy('purchase_order_id');

        if ($total_production_report_query->count() > 0) {
            $total_production_report = $total_production_report_query->paginate(18);
            $buyer_wise_report_html = '';
            $buyer_wise_report_html .= '<tr class="font-weight-bold text-danger tr-height">';
            $buyer_wise_report_html .= '<td colspan="17">No Data</td>';
            $buyer_wise_report_html .= '</tr>';
            if ($request->ajax()) {
                $print = 0; // No Print
                $buyer_wise_report_html = view('sewingdroplets::reports.includes.buyer_wise_report_inc', array('total_production_report' => $total_production_report, 'print' => $print))->render();
            }
            $status = 200;
            return response()->json([
                'status' => $status, 'html' => $buyer_wise_report_html,
                'buyer_id' => $buyer_id,
                'order_id' => $order_id
            ]);
        } else {
            $html = '';
            $order_info_data = null;
            $html .= '<tr class="font-weight-bold text-danger tr-height">';
            $html .= '<td colspan="17">No Data</td>';
            $html .= '</tr>';
            $status = 500;
            return response()->json(['status' => $status, 'html' => $html, 'buyer_id' => $buyer_id]);
        }
    }

    public function getBuyerWiseReportDownload($type, $buyer_id, $order_id, $page)
    {
        $total_production_report = $this->getBuyerWiseReportSewingOutputForDownload($buyer_id, $order_id, $page);
        $print = 1;
        $order = Buyer::where('id', $order_id)->first();
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('sewingdroplets::reports.downloads.pdf.buyer-wise-sewing-output-report-download',
                    compact('total_production_report', 'print', 'order')
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('booking-wise-sewing-output-report.pdf');
        } else {
            return \Excel::download(new BuyerWiseSewingOutputReportExport($buyer_id, $order_id), 'buyer-wise-sewing-output-report.xlsx');
        }
    }

    private function getBuyerWiseReportSewingOutputForDownload($buyer_id, $order_id, $page)
    {
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $total_production_report = TotalProductionReport::with([
            'order',
            'purchaseOrder',
            'colors'
        ])->where([
            'buyer_id' => $buyer_id,
            'order_id' => $order_id
        ])
            ->orderBy('purchase_order_id')
            ->paginate(18);

        return $total_production_report;
    }

    public function orderWiseReportForm()
    {
        $buyers = Buyer::pluck('name', 'id')->all();
        return view('sewingdroplets::reports.order_wise_report', [
            'buyers' => $buyers
        ]);
    }

    public function getStyleWiseSewingOutputReport($order_id)
    {
        $total_production_report = TotalProductionReport::with('buyer', 'order', 'purchaseOrder')
            ->where('order_id', $order_id)
            ->get();

        $reports = [];
        foreach ($total_production_report->groupBy('purchase_order_id') as $key => $reportByOrder) {
            $buyer_name = $reportByOrder->first()->buyer->name ?? '';
            $style_name = $reportByOrder->first()->order->order_style_no ?? 'Order/Style';
            $order_no = $reportByOrder->first()->purchaseOrder->po_no ?? '';
            $order_qty = $reportByOrder->first()->purchaseOrder->po_quantity ?? 0;

            $total_cutting_of_order = 0;
            $total_cutting_rejection_of_order = 0;
            $total_sent_of_order = 0;
            $total_received_of_order = 0;
            $total_print_rejection_of_order = 0;
            $todays_input_of_order = 0;
            $total_input_of_order = 0;
            $todays_sewing_output_of_order = 0;
            $total_sewing_output_of_order = 0;
            $total_sewing_rejection_of_order = 0;
            foreach ($reportByOrder as $report) {
                $total_cutting_of_order += $report->total_cutting ?? 0;
                $total_cutting_rejection_of_order += $report->total_cutting_rejection ?? 0;
                $total_sent_of_order += $report->total_sent ?? 0;
                $total_received_of_order += $report->total_received ?? 0;
                $total_print_rejection_of_order += $report->total_print_rejection ?? 0;
                $todays_input_of_order += $report->todays_input ?? 0;
                $total_input_of_order += $report->total_input ?? 0;
                $todays_sewing_output_of_order += $report->todays_sewing_output ?? 0;
                $total_sewing_output_of_order += $report->total_sewing_output ?? 0;
                $total_sewing_rejection_of_order += $report->total_sewing_rejection ?? 0;
            }
            $reports[$key]['buyer_name'] = $buyer_name;
            $reports[$key]['style'] = $style_name;
            $reports[$key]['order_no'] = $order_no;
            $reports[$key]['order_quantity'] = $order_qty;
            $reports[$key]['total_cutting'] = $total_cutting_of_order;
            $reports[$key]['total_cutting_rejection'] = $total_cutting_rejection_of_order;
            $reports[$key]['total_sent'] = $total_sent_of_order;
            $reports[$key]['total_received'] = $total_received_of_order;
            $reports[$key]['total_print_rejection'] = $total_print_rejection_of_order;
            $reports[$key]['todays_input'] = $todays_input_of_order;
            $reports[$key]['total_input'] = $total_input_of_order;
            $reports[$key]['todays_sewing_output'] = $todays_sewing_output_of_order;
            $reports[$key]['total_sewing_output'] = $total_sewing_output_of_order;
            $reports[$key]['total_sewing_rejection'] = $total_sewing_rejection_of_order;
        }
        return $reports;
    }

    public function getStyleWiseSewingOutputReportDownload($type, $order_id)
    {
        $data = $this->getStyleWiseSewingOutputReport($order_id);
        $style_query = Order::where('id', $order_id)->first();
        $style = $style_query->order_style_no ?? '';
        $buyer = $style_query->buyer->name ?? '';
        if ($type == 'pdf') {
            $pdf = \PDF::loadView('sewingdroplets::reports.downloads.pdf.style-wise-sewing-output-report-download', compact('data', 'style', 'buyer'), [], [
                'format' => 'A4-L'
            ]);
            return $pdf->download('style-wise-sewing-output-report.pdf');
        } else {
            return \Excel::download(new StyleWiseSewingReportExport($data, $style, $buyer), 'reference-wise-sewing-output-report.xlsx');

            /*\Excel::create('Style Wise Sewing Output Report ', function ($excel) use ($data, $style, $buyer) {
                $excel->sheet('Style Wise Sewing Report', function ($sheet) use ($data, $style, $buyer) {
                    $sheet->loadView('sewingdroplets::reports.downloads.excels.style-wise-sewing-output-report-download', compact('data', 'style', 'buyer'));
                });
            })->export('xls');*/
        }
    }

    public function orderWiseReportView($purchase_order_id)
    {
        $result = [];
        $total_order_qty = 0;
        $total_cutting_qty = 0;
        $total_rejection = 0;
        $total_today_input = 0;
        $total_total_input = 0;
        $total_today_output = 0;
        $total_total_output = 0;
        $total_wip = 0;
        $total_in_line_wip = 0;

        $report_size_wise = [];
        $total_report = [];

        $order_sizes = PurchaseOrderDetail::with('color', 'size')
            ->where('purchase_order_id', $purchase_order_id)
            ->orderBy('purchase_order_id', 'ASC')
            ->get();

        foreach ($order_sizes as $key => $size) {
            $report_size_wise[$key]['color'] = $size->color->name;
            $report_size_wise[$key]['size'] = $size->size->name;
            $report_size_wise[$key]['size_order_qty'] = $size->quantity;

            $bundles = BundleCard::where(['color_id' => $size->color_id, 'purchase_order_id' => $purchase_order_id, 'size_id' => $size->size_id, 'status' => 1])
                ->with('cutting_inventory', 'cutting_inventory.cutting_inventory_challan', 'sewingoutput')
                ->get();

            $size_cutting_qty = 0;
            $today_input = 0;
            $total_input = 0;
            $today_output = 0;
            $total_output = 0;
            $rejection = 0;

            foreach ($bundles as $bundle) {
                // size wise cutting qty
                $size_cutting_qty += $bundle->quantity - $bundle->total_rejection;
                $rejection += $bundle->total_rejection + $bundle->print_rejection + $bundle->sewing_rejection;

                // today & total input to line
                if (isset($bundle->cutting_inventory->cutting_inventory_challan->line_id)) {
                    if ($bundle->cutting_inventory->cutting_inventory_challan->input_date == date('Y-m-d')) {
                        $today_input += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection;
                    }
                    $total_input += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection;;
                }
                // today & total sewing output
                if (isset($bundle->sewingoutput)) {
                    if (date('Y-m-d', strtotime($bundle->sewingoutput->created_at)) == date('Y-m-d')) {
                        $today_output += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection - $bundle->sewing_rejection;
                    }
                    $total_output += $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection - $bundle->sewing_rejection;
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
            $total_order_qty += $size->quantity;
            $total_cutting_qty += $size_cutting_qty;
            $total_rejection += $rejection;
            $total_today_input += $today_input;
            $total_total_input += $total_input;
            $total_today_output += $today_output;
            $total_total_output += $total_output;
            $total_wip += $wip;
            $total_in_line_wip += $in_line_wip;
        }

        $total_report['total_order_qty'] = $total_order_qty;
        $total_report['total_cutting_qty'] = $total_cutting_qty;
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

    public function orderWiseSewingOutputReportDownload($type, $purchase_order_id)
    {
        $data = $this->orderWiseReportView($purchase_order_id);
        $order_query = PurchaseOrder::where('id', $purchase_order_id)->first();
        $order_no = $order_query->po_no;
        $buyer = $order_query->buyer->name ?? '';
        $style = $order_query->order->order_style_no ?? '';
        $booking_no = $order_query->order->booking_no ?? '';
        $report_head = [
            'order_no' => $order_no,
            'buyer' => $buyer,
            'style' => $style,
            'booking_no' => $booking_no
        ];
        if ($type == 'pdf') {
            $pdf = \PDF::loadView('sewingdroplets::reports.downloads.pdf.order-wise-sewing-output-report-download', $data, $report_head, [], [
                'format' => 'A4-L'
            ]);
            return $pdf->download('order-wise-sewing-output-report.pdf');
        } else {
            return \Excel::download(new PoWiseSewingReportExport($data, $report_head), 'po-wise-sewing-output-report.xlsx');

            /*\Excel::create('Order Wise Sewing Output Report ', function ($excel) use ($data,$report_head) {
                $excel->sheet('Order Wise Sewing Report', function ($sheet) use ($data,$report_head) {
                    $sheet->loadView('sewingdroplets::reports.downloads.excels.order-wise-sewing-output-report-download', $data,$report_head);
                });
            })->export('xls');*/
        }
    }

    // FOR TNA
    public static function getOrderWiseActualSewingDateInfo($order_id)
    {
        // For Sewing Date
        $bundleCardQuery = BundleCard::where(['order_id' => $order_id, 'status' => 1])->whereNotNull('input_date');
        $actual_start = '';
        $actual_end = '';
        $duration = '';
        if ($bundleCardQuery->count()) {
            $order_qty = Order::findOrFail($order_id)->total_quantity;
            $firstBundle = $bundleCardQuery->orderBy('input_date', 'asc')->first();
            $actual_start = $firstBundle->input_date;
            $bundleCardQueryClone = clone $bundleCardQuery;
            $sewingOutputQuery = $bundleCardQueryClone->whereNotNull('sewing_output_date');
            $sewingOutputQty = $sewingOutputQuery->sum('quantity') - $sewingOutputQuery->sum('total_rejection') - $sewingOutputQuery->sum('print_rejection') - $sewingOutputQuery->sum('sewing_rejection');
            if ($sewingOutputQty >= $order_qty) {
                $actual_end = $sewingOutputQuery->orderBy('sewing_output_date', 'desc')->first()->sewing_output_date;
                $duration = self::calculateDays($actual_start, $actual_end);
            }
        }

        return [
            'actual_start' => $actual_start,
            'actual_end' => $actual_end,
            'actual_duration' => $duration,
        ];
    }

    public static function calculateDays($start, $end)
    {
        if (!$start || !$end) return null;

        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);

        $days = $startDate->diffInDays($endDate, false) + 1;
        return $days;
    }
}
