<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\AllPoInputSummaryReportExport;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\BuyerWiseInputReportExport;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\ColorWiseInputReportExport;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\DailyInputStatusReportExport;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\DateWiseInputReportExport;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\MonthWiseInputReportExport;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\StyleWiseInputReportExport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DateWiseSewingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use PDF, Session;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\ArchivedBundleCard;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\OrderWiseInputReportExport;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\SizeWiseInputReportExport;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderColorSizeController extends Controller
{
    public function orderWiseInputReport()
    {
        $booking_nos = Order::pluck('job_no', 'id')
            ->prepend('Select a booking no', '')
            ->all();

        $order_wise_report = $this->orderWiseInputReportData(request('booking_no_id'));

        return view('inputdroplets::reports.order_wise_input', [
            'order_wise_input' => $order_wise_report,
            'booking_nos' => $booking_nos,
            'print' => 0
        ]);
    }

    public function orderWiseInputReportData($bookingNoId)
    {
        $report_data = TotalProductionReport::with([
            'buyer:id,name',
            'order:id,style_name',
            'purchaseOrder:id,po_no,po_quantity'
        ]);
        if ($bookingNoId) {
            $report_data = $report_data->where('order_id', $bookingNoId);
        }
        $report_data = $report_data->orderBy('buyer_id', 'desc')->paginate(PAGINATION);

        return $report_data;
    }

    public function orderWiseInputReportDownload()
    {
        $type = request('type');
        $bookingNoId = request('booking_no_id');
        $page = request('page');

        if ($type == 'pdf') {
            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
            $order_wise_input = $this->orderWiseInputReportData($bookingNoId);
            $print = 1;
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('inputdroplets::reports.downloads.order-wise-input-download',
                    compact('order_wise_input', 'print')
                )->setPaper('a4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('orders-wise-input-report.pdf');
        } else {
            return \Excel::download(new AllPoInputSummaryReportExport($bookingNoId), 'order-wise-input-report.xlsx');
        }
    }

    public function buyerWiseInputReport()
    {
        return view('inputdroplets::reports.buyer_wise_input');
    }

    public function getBuyerWiseInputReportData(Request $request)
    {
        $buyer_id = $request->buyer_id;
        $total_production_report_query = TotalProductionReport::with('buyer', 'order', 'purchaseOrder')
            ->where('buyer_id', $buyer_id)
            ->orderBy('purchase_order_id');

        if ($total_production_report_query->count() > 0) {
            $total_production_report = $total_production_report_query->paginate(18);
            $buyer_wise_report_html = '';
            $buyer_wise_report_html .= '<tr>';
            $buyer_wise_report_html .= '<td colspan="13">No Data</td>';
            $buyer_wise_report_html .= '</tr>';
            if ($request->ajax()) {
                $print = 0; // No Print
                $buyer_wise_report_html = view('inputdroplets::reports.includes.buyer_wise_report_inc', array('total_production_report' => $total_production_report, 'print' => $print))->render();
            }
            $status = 200;
            return response()->json(['status' => $status, 'html' => $buyer_wise_report_html, 'buyer_id' => $buyer_id]);
        } else {
            $html = '';
            $order_info_data = null;
            $html .= '<tr>';
            $html .= '<td colspan="13">No Data</td>';
            $html .= '</tr>';
            $status = 500;
            return response()->json(['status' => $status, 'html' => $html, 'buyer_id' => $buyer_id]);
        }
    }

    public function buyerWiseInputReportDownload($type, $buyer_id, $page)
    {
        $data['total_production_report'] = $this->getBuyerWiseInputReportDataForDownload($buyer_id, $page);
        $data['buyer'] = Buyer::where('id', $buyer_id)->first()->name ?? '';
        $data['print'] = 1;
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('inputdroplets::reports.downloads.pdf.buyer-wise-sewing-line-input-report-download', $data)
                ->setPaper('a4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('buyer-wise-input-report.pdf');
        } else {
            return \Excel::download(new BuyerWiseInputReportExport($buyer_id), 'buyer-wise-input-report.xlsx');
        }
    }

    private function getBuyerWiseInputReportDataForDownload($buyer_id, $page)
    {
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $total_production_report = TotalProductionReport::with([
            'purchaseOrder',
            'order',
            'colors'
        ])
            ->where('buyer_id', $buyer_id)
            ->orderBy('purchase_order_id')
            ->paginate(18);

        return $total_production_report;
    }

    public function monthWiseInputReport(Request $request)
    {
        $lines = null;
        $date_wise_input = null;
        $from_date = $request->from_date ?? now()->startOfMonth()->toDateString();
        $to_date = $request->to_date ?? now()->toDateString();

        if ($request->isMethod('POST')) {
            $request->validate([
                'floor_id' => 'required',
                'line_id' => 'required',
                'from_date' => 'required|date',
                'to_date' => 'required|date|after_or_equal:from_date',
            ]);
            $from_date = $request->from_date;
            $to_date = $request->to_date;

            $frmDate = Carbon::parse($from_date);
            $toDate = Carbon::parse($to_date);
            $diff = $frmDate->diffInDays($toDate);

            if ($diff > 31) {
                Session::flash('error', 'Please enter maximum one month date range');
                return redirect('date-range-or-month-wise-sewing-input');
            }
            $date_wise_input = $this->getMonthWiseInputReportData($from_date, $to_date, $request->line_id);
        } elseif ($request->from_date  && $request->to_date && $request->line_id) {
            $from_date = $request->from_date;
            $to_date = $request->to_date;

            $frmDate = Carbon::parse($from_date);
            $toDate = Carbon::parse($to_date);
            $diff = $frmDate->diffInDays($toDate);

            if ($diff > 31) {
                Session::flash('error', 'Please enter maximum one month date range');
                return redirect('date-range-or-month-wise-sewing-input');
            }

            $date_wise_input = $this->getMonthWiseInputReportData($from_date, $to_date, $request->line_id);
        }

        $floors = Floor::pluck('floor_no', 'id')->all();
        if ($request->floor_id) {
            $lines = Line::where('floor_id', $request->floor_id)->pluck('line_no', 'id')->all();
        }

        return view('inputdroplets::reports.month_wise_input', [
            'date_wise_input' => $date_wise_input,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'floors' => $floors,
            'lines' => $lines,
            'floor_id' => $request->floor_id,
            'line_id' => $request->line_id
        ]);
    }

    public function getMonthWiseInputReportData($from_date, $to_date, $line_id)
    {
        return CuttingInventoryChallan::withoutGlobalScopes()
            ->Join('cutting_inventories', 'cutting_inventories.challan_no', 'cutting_inventory_challans.challan_no')
            ->Join('bundle_cards', 'bundle_cards.id', 'cutting_inventories.bundle_card_id')
            ->whereNotNull('cutting_inventory_challans.line_id')
            ->whereNull('cutting_inventories.deleted_at')
            ->whereNull('bundle_cards.deleted_at')
            ->whereNull('cutting_inventory_challans.deleted_at')
            ->whereDate('cutting_inventory_challans.updated_at', '>=', $from_date)
            ->whereDate('cutting_inventory_challans.updated_at', '<=', $to_date)
            ->where([
                'cutting_inventory_challans.line_id' => $line_id,
                'cutting_inventory_challans.factory_id' => factoryId()
            ])
            ->with('line', 'buyer', 'order', 'purchaseOrder')
            ->selectRaw('cutting_inventory_challans.line_id, cutting_inventory_challans.challan_no, bundle_cards.buyer_id, bundle_cards.order_id, bundle_cards.purchase_order_id,
                SUM(quantity) as quantity_sum, SUM(total_rejection) as total_rejection_sum, SUM(print_rejection) as print_rejection_sum
            ')
            ->groupBy('cutting_inventory_challans.line_id', 'cutting_inventory_challans.challan_no', 'bundle_cards.buyer_id', 'bundle_cards.order_id', 'bundle_cards.purchase_order_id')
            ->get();
    }

    public function getMonthWiseInputReportDownload($type, $from_date, $to_date, $line_id)
    {
        $data['date_wise_input'] = $this->getMonthWiseInputReportData($from_date, $to_date, $line_id);
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('inputdroplets::reports.downloads.pdf.month-wise-sewing-input-download', $data)
                ->setPaper('a4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('month-wise-sewing-input-report.pdf');
        } else {
            return \Excel::download(new MonthWiseInputReportExport($data), 'month-wise-sewing-input-report.xlsx');
        }
    }

    public function getDailyInputStatusReport(Request $request) {
        $date = $request->date ?? date('Y-m-d');
        $data = $this->getDailyInputData($date);

        return view('inputdroplets::reports.daily_input_status', [
            'daily_input_status' => $data,
            'date' => $date
        ]);
    }

    public function getDailyInputData($date)
    {
        return FinishingProductionReport::query()
            ->with([
                'buyer:id,name',
                'order:id,style_name',
                'purchaseOrder.purchaseOrderDetails',
                'floor:id,floor_no',
                'line:id,line_no',
            ])
            ->groupBy('buyer_id', 'order_id', 'purchase_order_id', 'floor_id', 'line_id')
            ->selectRaw('buyer_id, order_id, purchase_order_id, floor_id, line_id, sum(sewing_input) as sewing_input')
            ->whereDate('production_date', $date)
            ->where('sewing_input', '>', 0)
            ->orderBy('floor_id')
            ->get();
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function getDailyInputStatusDownload($type, $date)
    {
        $data['daily_input_status'] = $this->getDailyInputData($date);
        $data['date'] = $date;
        if ($type == 'pdf') {
            $pdf = \PDF::loadView('inputdroplets::reports.daily_input_status', $data)
                ->setPaper('a4')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream("{$date}_daily-input-status-report.pdf");
        } else {
            return \Excel::download(new DailyInputStatusReportExport($data), "{$date}_daily-input-status-report.xlsx");
        }

    }

    public function getDateWiseInput(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');

        $date_wise_input = $this->getDateWiseInputData($date);

            return view('inputdroplets::reports.date_wise_input_report_modify', [
            'date_wise_input' => $date_wise_input,
            'date' => $date
        ]);
    }

    public function getDateWiseInputData($date)
    {
        return FinishingProductionReport::whereDate('production_date', $date)
            ->orderBy('floor_id')
            ->where('sewing_input', '>', 0)
            ->get();
    }

    public function getDateWiseInputDownload($type, $date)
    {
        $data['date_wise_input'] = $this->getDateWiseInputData($date);
        $data['line_wise_count'] = $data['date_wise_input']->groupBy('line_id')->count();
        $data['date'] = $date;
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('inputdroplets::reports.downloads.pdf.date-wise-sewing-input-download', $data)
                ->setPaper('a4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('inventory-challan-count-report.pdf');
        } else {
            return \Excel::download(new DateWiseInputReportExport($data), 'date-wise-input-report.xlsx');
        }

    }

    public function bookingNoPoAndColorReport()
    {
        // this query will run when user first time login
        TotalProductionReport::whereDate('updated_at', '!=', Carbon::now()->toDateString())
            ->update([
                'todays_cutting' => 0,
                'todays_cutting_rejection' => 0,
                'todays_sent' => 0,
                'todays_received' => 0,
                'todays_print_rejection' => 0,
                'todays_embroidary_sent' => 0,
                'todays_embroidary_received' => 0,
                'todays_embroidary_rejection' => 0,
                'todays_input' => 0,
                'todays_sewing_output' => 0,
                'todays_sewing_rejection' => 0,
                'todays_washing_sent' => 0,
                'todays_washing_received' => 0,
                'todays_washing_rejection' => 0,
                'todays_received_for_poly' => 0,
                'todays_poly' => 0,
                'todays_poly_rejection' => 0,
                'todays_cartoon' => 0,
                'todays_pcs' => 0,
                'todays_shipment_qty' => 0
            ]);

        return view('inputdroplets::reports.booking_no_po_and_color_wise_input');
    }

    public function getStyleWiseInputReport($order_id)
    {
        $data = $this->getStyleWiseInputReportData($order_id);

        return response()->json($data);
    }

    public function getStyleWiseInputReportData($order_id)
    {
        $po_wise_production_report = TotalProductionReport::with('purchaseOrder:id,po_no,po_quantity')
            ->selectRaw(
                'purchase_order_id,
                sum(todays_cutting - todays_cutting_rejection) as todays_cutting,
                sum(total_cutting - total_cutting_rejection) as cutting_qty,
                sum(total_cutting_rejection) as cutting_rejection,
                sum(todays_sent) as todays_print_sent,
                sum(total_sent) as print_sent,
                sum(todays_received) as todays_print_received,
                sum(total_received) as print_received,
                sum(total_print_rejection) as print_rejection,
                sum(todays_embroidary_sent) as todays_embr_sent,
                sum(total_embroidary_sent) as embr_sent,
                sum(todays_embroidary_received) as todays_embr_received,
                sum(total_embroidary_received) as embr_received,
                sum(total_embroidary_rejection) as embr_rejection,
                sum(todays_input) as todays_input,
                sum(total_input) as input_qty,
                sum(todays_sewing_output) as todays_sewing_output,
                sum(total_sewing_output) as sewing_output_qty,
                sum(total_sewing_rejection) as sewing_rejection,
                sum(total_input - total_sewing_output - total_sewing_rejection) as sewing_balance'
            )
            ->where('order_id', $order_id)
            ->groupBy('purchase_order_id')
            ->orderBy('purchase_order_id', 'desc')
            ->get();

        $total_data = [
            'todays_cutting' => $po_wise_production_report->sum('todays_cutting'),
            'total_cutting' => $po_wise_production_report->sum('cutting_qty'),
            'total_cutting_rejection' => $po_wise_production_report->sum('cutting_rejection'),
            'todays_sent' => $po_wise_production_report->sum('todays_print_sent'),
            'total_sent' => $po_wise_production_report->sum('print_sent'),
            'todays_received' => $po_wise_production_report->sum('todays_print_received'),
            'total_received' => $po_wise_production_report->sum('print_received'),
            'total_print_rejection' => $po_wise_production_report->sum('print_rejection'),
            'todays_embr_sent' => $po_wise_production_report->sum('todays_embr_sent'),
            'total_embr_sent' => $po_wise_production_report->sum('embr_sent'),
            'todays_embr_received' => $po_wise_production_report->sum('todays_embr_received'),
            'total_embr_received' => $po_wise_production_report->sum('embr_received'),
            'total_embr_rejection' => $po_wise_production_report->sum('embr_rejection'),
            'todays_input' => $po_wise_production_report->sum('todays_input'),
            'total_input' => $po_wise_production_report->sum('input_qty'),
            'todays_sewing_output' => $po_wise_production_report->sum('todays_sewing_output'),
            'total_sewing_output' => $po_wise_production_report->sum('sewing_output_qty'),
            'total_sewing_rejection' => $po_wise_production_report->sum('sewing_rejection'),
            'total_sewing_balance' => $po_wise_production_report->sum('sewing_balance')
        ];

        return [
            'po_wise_production_report' => $po_wise_production_report,
            'total_data' => $total_data
        ];
    }

    public function getStyleInputReportDownload($type, $order_id)
    {
        $data['data'] = $this->getStyleWiseInputReportData($order_id);
        $order_query = Order::where('id', $order_id)->first();
        $data['buyer'] = $order_query->buyer->name ?? '';
        $data['style'] = $order_query->style_name ?? '';

        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('inputdroplets::reports.downloads.pdf.get-style-wise-sewing-input-report-download', $data)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('style-wise-sewing-input-report-'.now()->toDateString().'.pdf');
        } else {
            return \Excel::download(new StyleWiseInputReportExport($data), 'style-wise-sewing-input-report-'.now()->toDateString().'.xlsx');
        }
    }

    public function getOrderColorWiseInputReport($order_id, $purchase_order_id, $garments_item_id)
    {
        $data = $this->getOrderColorWiseInputReportData($order_id, $purchase_order_id, $garments_item_id);

        return response()->json($data);
    }

    public function getOrderColorWiseInputReportData($order_id, $purchase_order_id, $garments_item_id)
    {
        $color_wise_production_report = TotalProductionReport::with('color:id,name')
            ->selectRaw(
                'color_id,
                sum(todays_cutting - todays_cutting_rejection) as todays_cutting,
                sum(total_cutting - total_cutting_rejection) as cutting_qty,
                sum(total_cutting_rejection) as cutting_rejection,
                sum(todays_sent) as todays_print_sent,
                sum(total_sent) as print_sent,
                sum(todays_received) as todays_print_received,
                sum(total_received) as print_received,
                sum(total_print_rejection) as print_rejection,
                sum(todays_embroidary_sent) as todays_embr_sent,
                sum(total_embroidary_sent) as embr_sent,
                sum(todays_embroidary_received) as todays_embr_received,
                sum(total_embroidary_received) as embr_received,
                sum(total_embroidary_rejection) as embr_rejection,
                sum(todays_input) as todays_input,
                sum(total_input) as input_qty,
                sum(todays_sewing_output) as todays_sewing_output,
                sum(total_sewing_output) as sewing_output_qty,
                sum(total_sewing_rejection) as sewing_rejection,
                sum(total_input - total_sewing_output - total_sewing_rejection) as sewing_balance'
            )->where([
                'order_id' => $order_id,
                'garments_item_id' => $garments_item_id,
            ]);
        if ($purchase_order_id != 'all') {
            $color_wise_production_report = $color_wise_production_report->where('purchase_order_id', $purchase_order_id);
            $purchase_order_ids[] = $purchase_order_id;
        } else {
            $purchase_order_ids = TotalProductionReport::where([
                'order_id' => $order_id,
                'garments_item_id' => $garments_item_id,
            ])
                ->pluck('purchase_order_id')
                ->toArray();
            $purchase_order_ids = array_unique($purchase_order_ids);
        }
        $color_wise_production_report = $color_wise_production_report->groupBy('color_id')->get();

        $color_dropdown = [];
        foreach ($color_wise_production_report as $key => $color_wise_production) {
            $color_name = $color_wise_production->color->name ?? 'Color';
            $color_dropdown[$color_wise_production->color_id] = $color_name;
            $color_wise_production_report[$key]->color_name = $color_name;
            $color_wise_order_qty = PurchaseOrderDetail::where([
                    'color_id' => $color_wise_production->color_id,
                    'garments_item_id' => $garments_item_id,
                ])
                ->whereIn('purchase_order_id', $purchase_order_ids)
                ->sum('quantity');

            $color_wise_production_report[$key]->color_wise_order_qty = $color_wise_order_qty;
            $color_wise_production_report[$key]->left_qty = $color_wise_order_qty - $color_wise_production->cutting_qty;
        }

        $total_data = $this->getOrderColorWiseTotalInputData($color_wise_production_report);

        return [
            'color_wise_production_report' => $color_wise_production_report,
            'color_dropdown' => $color_dropdown,
            'total_data' => $total_data
        ];
    }

    public function getOrderColorWiseInputReportDownload($type, $order_id, $purchase_order_id, $garments_item_id)
    {
        $data = $this->getOrderColorWiseInputReportData($order_id, $purchase_order_id, $garments_item_id);
        $order_query = Order::where('id', $order_id)->first();
        $buyer = $order_query->buyer->name ?? '';
        $style = $order_query->style_name ?? '';
        $booking_no = $order_query->reference_no ?? '';

        $purchase_order_query = $purchase_order_id && $purchase_order_id != 'all' ? PurchaseOrder::where('id', $purchase_order_id)->first() : null;

        $po_no = $purchase_order_query ? $purchase_order_query->po_no : 'ALL';

        $report_head = [
            'booking_no' => $booking_no,
            'po_no' => $po_no,
            'buyer' => $buyer,
            'style' => $style,
        ];

        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('inputdroplets::reports.downloads.pdf.order-color-size-wise-sewing-input-report-download', $data, $report_head)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('color-wise-sewing-input-report-'.now()->toDateString().'.pdf');
        } else {
            return \Excel::download(new ColorWiseInputReportExport($data, $report_head), 'color-wise-sewing-input-report-'.now()->toDateString().'.xlsx');
        }
    }

    public function getOrderColorWiseTotalInputData($color_wise_production_report)
    {
        $total_data = [
            'total_color_wise_order_qty' => $color_wise_production_report->sum('color_wise_order_qty'),
            'total_todays_cutting' => $color_wise_production_report->sum('todays_cutting'),
            'total_cutting' => $color_wise_production_report->sum('cutting_qty'),
            'total_cutting_rejection' => $color_wise_production_report->sum('cutting_rejection'),
            'total_left_qty' => $color_wise_production_report->sum('left_qty'),
            'total_todays_sent' => $color_wise_production_report->sum('todays_print_sent'),
            'total_sent' => $color_wise_production_report->sum('print_sent'),
            'total_todays_received' => $color_wise_production_report->sum('todays_print_received'),
            'total_received' => $color_wise_production_report->sum('print_received'),
            'total_print_rejection' => $color_wise_production_report->sum('print_rejection'),
            'total_todays_embr_sent' => $color_wise_production_report->sum('todays_embr_sent'),
            'total_embr_sent' => $color_wise_production_report->sum('embr_sent'),
            'total_todays_embr_received' => $color_wise_production_report->sum('todays_embr_received'),
            'total_embr_received' => $color_wise_production_report->sum('embr_received'),
            'total_embr_rejection' => $color_wise_production_report->sum('embr_rejection'),
            'total_todays_input' => $color_wise_production_report->sum('todays_input'),
            'total_input' => $color_wise_production_report->sum('input_qty'),
            'total_todays_sewing_output' => $color_wise_production_report->sum('todays_sewing_output'),
            'total_sewing_output' => $color_wise_production_report->sum('sewing_output_qty'),
            'total_sewing_rejection' => $color_wise_production_report->sum('sewing_rejection'),
            'total_sewing_balance' => $color_wise_production_report->sum('sewing_balance')
        ];

        return $total_data;
    }

    public function getSizeWiseInputReport($order_id, $po_id, $color_id, $garments_item_id)
    {
        $data = $this->getSizeWiseInputReportData($order_id, $po_id, $color_id, $garments_item_id);

        return response()->json($data);
    }
    private function getSizeWiseInputReportData($order_id, $po_id, $color_id, $garments_item_id)
    {
        $bundle_cards = BundleCard::with([
            'size:id,name',
            'order:id',
            'order.purchaseOrders:id,order_id',
            'cutting_inventory:id,bundle_card_id,challan_no',
            'print_inventory:id,bundle_card_id,challan_no',
            'sewingoutput:id,bundle_card_id'
        ])->where([
            'order_id' => $order_id,
            'color_id' => $color_id,
            'garments_item_id' => $garments_item_id,
            'status' => 1
        ]);
        $archived_bundle_cards = ArchivedBundleCard::with([
            'size:id,name',
            'order:id',
            'order.purchaseOrders:id,order_id',
        ])->where([
            'order_id' => $order_id,
            'color_id' => $color_id,
            'garments_item_id' => $garments_item_id,
            'status' => 1
        ]);

        if ($po_id != 'all') {
            $bundle_cards = $bundle_cards->where('purchase_order_id', $po_id); // po_id = purchase_order_id
            $archived_bundle_cards = $archived_bundle_cards->where('purchase_order_id', $po_id); // po_id = purchase_order_id
        }
        $bundle_cards = $bundle_cards->get();
        $archived_bundle_cards = $archived_bundle_cards->get();

        // get all unique purchase order id for this given order id
        
        $purchaseOrdrIds = array_unique($bundle_cards->pluck('purchase_order_id')->unique()->toArray());
        $purchaseOrdrIds = array_unique(array_merge($purchaseOrdrIds, $archived_bundle_cards->pluck('purchase_order_id')->unique()->toArray()));
        $poItemColorSizes = PoColorSizeBreakdown::query()
            ->when($po_id != 'all', function($query) use($po_id) {
                $query->where('purchase_order_id', $po_id);
            })
            ->when($po_id == 'all', function($query) use($order_id) {
                $query->where('order_id', $order_id);
            })
            ->get();
        $sortedSizes = [];
        if ($poItemColorSizes && count($poItemColorSizes)) {
            foreach ($poItemColorSizes as $poItemColorSize) {
                $sortedSizes = array_unique(array_merge($sortedSizes, $poItemColorSize->sizes));
            }
        }
        $size_wize_report = [];

        foreach ($bundle_cards->groupBy('size_id') as $sizeId => $size_wise_bundle_card) {
            $todays_print_sent_qty = 0;
            $print_sent_qty = 0;
            $todays_print_received_qty = 0;
            $print_received_qty = 0;
            $print_rejection_qty = 0;
            $todays_embr_sent_qty = 0;
            $embr_sent_qty = 0;
            $todays_embr_received_qty = 0;
            $embr_received_qty = 0;
            $embr_rejection_qty = 0;
            $todays_input_qty = 0;
            $input_qty = 0;
            $todays_output_qty = 0;
            $output_qty = 0;
            $sewing_rejection_qty = 0;
            $today = Carbon::today()->toDateString();

            $size_wise_order_qty = PurchaseOrderDetail::where([
                'color_id' => $size_wise_bundle_card->first()->color_id,
                'size_id' => $sizeId
            ])
                ->whereIn('purchase_order_id', $purchaseOrdrIds)
                ->sum('quantity');

            foreach ($size_wise_bundle_card as $bundle) {
                if ($bundle->print_sent_date) {
                    if ($bundle->print_sent_date == $today) {
                        $todays_print_sent_qty += $bundle->quantity - $bundle->total_rejection;
                    }
                    $print_sent_qty += $bundle->quantity - $bundle->total_rejection;
                }
                if ($bundle->print_received_date) {
                    if ($bundle->print_received_date == $today) {
                        $todays_print_received_qty += $bundle->quantity
                            - $bundle->total_rejection
                            - $bundle->print_rejection;
                    }
                    $print_rejection_qty += $bundle->print_rejection;
                    $print_received_qty += $bundle->quantity
                        - $bundle->total_rejection
                        - $bundle->print_rejection;
                }
                if ($bundle->embroidary_sent_date) {
                    if ($bundle->embroidary_sent_date == $today) {
                        $todays_embr_sent_qty += $bundle->quantity - $bundle->total_rejection;
                    }
                    $embr_sent_qty += $bundle->quantity - $bundle->total_rejection;
                }
                if ($bundle->embroidary_received_date) {
                    if ($bundle->embroidary_received_date == $today) {
                        $todays_embr_received_qty += $bundle->quantity - $bundle->total_rejection - $bundle->embroidary_rejection;
                    }
                    $embr_rejection_qty += $bundle->embroidary_rejection;
                    $embr_received_qty += $bundle->quantity - $bundle->total_rejection - $bundle->embroidary_rejection;
                }
                if ($bundle->input_date) {
                    if ($bundle->input_date == $today) {
                        $todays_input_qty += $bundle->quantity
                            - $bundle->total_rejection
                            - $bundle->print_rejection
                            - $bundle->embroidary_rejection;
                    }
                    $input_qty += $bundle->quantity
                        - $bundle->total_rejection
                        - $bundle->print_rejection
                        - $bundle->embroidary_rejection;
                }
                if ($bundle->sewing_output_date) {
                    if ($bundle->sewing_output_date == $today) {
                        $todays_output_qty += $bundle->quantity
                            - $bundle->total_rejection
                            - $bundle->print_rejection
                            - $bundle->embroidary_rejection
                            - $bundle->sewing_rejection;
                    }
                    $sewing_rejection_qty += $bundle->sewing_rejection;
                    $output_qty += $bundle->quantity
                        - $bundle->total_rejection
                        - $bundle->print_rejection
                        - $bundle->embroidary_rejection
                        - $bundle->sewing_rejection;
                }
            }

            $cutting_rejection = $size_wise_bundle_card->sum('total_rejection');
            $cutting_qty = $size_wise_bundle_card->sum('quantity') - $cutting_rejection;

            $todays_cutting_qty = $size_wise_bundle_card->where('cutting_date', $today)->sum('quantity')
                - $size_wise_bundle_card->where('cutting_date', $today)->sum('total_cutting_rejection');

            $sizeKey = array_search($sizeId, $sortedSizes) ?? $sizeId;

            $size_wize_report[$sizeKey]['size_name'] = $size_wise_bundle_card->first()->size->name ?? 'Size';
            $size_wize_report[$sizeKey]['size_wise_order_qty'] = $size_wise_order_qty;
            $size_wize_report[$sizeKey]['todays_cutting_qty'] = $todays_cutting_qty;
            $size_wize_report[$sizeKey]['cutting_qty'] = $cutting_qty;
            $size_wize_report[$sizeKey]['cutting_rejection'] = $cutting_rejection;
            $size_wize_report[$sizeKey]['left_qty'] = $size_wise_order_qty - $cutting_qty;
            $size_wize_report[$sizeKey]['todays_print_sent_qty'] = $todays_print_sent_qty;
            $size_wize_report[$sizeKey]['print_sent_qty'] = $print_sent_qty;
            $size_wize_report[$sizeKey]['todays_print_received_qty'] = $todays_print_received_qty;
            $size_wize_report[$sizeKey]['print_received_qty'] = $print_received_qty;
            $size_wize_report[$sizeKey]['print_rejection_qty'] = $print_rejection_qty;
            $size_wize_report[$sizeKey]['todays_embr_sent_qty'] = $todays_embr_sent_qty;
            $size_wize_report[$sizeKey]['embr_sent_qty'] = $embr_sent_qty;
            $size_wize_report[$sizeKey]['todays_embr_received'] = $todays_embr_received_qty;
            $size_wize_report[$sizeKey]['embr_received_qty'] = $embr_received_qty;
            $size_wize_report[$sizeKey]['embr_rejection_qty'] = $embr_rejection_qty;
            $size_wize_report[$sizeKey]['todays_input_qty'] = $todays_input_qty;
            $size_wize_report[$sizeKey]['input_qty'] = $input_qty;
            $size_wize_report[$sizeKey]['todays_output_qty'] = $todays_output_qty;
            $size_wize_report[$sizeKey]['output_qty'] = $output_qty;
            $size_wize_report[$sizeKey]['sewing_rejection_qty'] = $sewing_rejection_qty;
            $size_wize_report[$sizeKey]['sewing_balance'] = $input_qty - $output_qty - $sewing_rejection_qty;
        }
        
        foreach ($archived_bundle_cards->groupBy('size_id') as $sizeId => $archived_size_wise_bundle_card) {
            $todays_print_sent_qty = 0;
            $print_sent_qty = 0;
            $todays_print_received_qty = 0;
            $print_received_qty = 0;
            $print_rejection_qty = 0;
            $todays_embr_sent_qty = 0;
            $embr_sent_qty = 0;
            $todays_embr_received_qty = 0;
            $embr_received_qty = 0;
            $embr_rejection_qty = 0;
            $todays_input_qty = 0;
            $input_qty = 0;
            $todays_output_qty = 0;
            $output_qty = 0;
            $sewing_rejection_qty = 0;

            $size_wise_order_qty = PurchaseOrderDetail::where([
                'color_id' => $archived_size_wise_bundle_card->first()->color_id,
                'size_id' => $sizeId
            ])
                ->whereIn('purchase_order_id', $purchaseOrdrIds)
                ->sum('quantity');

            foreach ($archived_size_wise_bundle_card as $bundle) {
                if ($bundle->print_sent_date) {
                    $print_sent_qty += $bundle->quantity - $bundle->total_rejection;
                }
                if ($bundle->print_received_date) {
                    $print_rejection_qty += $bundle->print_rejection;
                    $print_received_qty += $bundle->quantity
                        - $bundle->total_rejection
                        - $bundle->print_rejection;
                }
                if ($bundle->embroidary_sent_date) {
                    $embr_sent_qty += $bundle->quantity - $bundle->total_rejection;
                }
                if ($bundle->embroidary_received_date) {
                    $embr_rejection_qty += $bundle->embroidary_rejection;
                    $embr_received_qty += $bundle->quantity - $bundle->total_rejection - $bundle->embroidary_rejection;
                }
                if ($bundle->input_date) {
                    $input_qty += $bundle->quantity
                        - $bundle->total_rejection
                        - $bundle->print_rejection
                        - $bundle->embroidary_rejection;
                }
                if ($bundle->sewing_output_date) {
                    $sewing_rejection_qty += $bundle->sewing_rejection;
                    $output_qty += $bundle->quantity
                        - $bundle->total_rejection
                        - $bundle->print_rejection
                        - $bundle->embroidary_rejection
                        - $bundle->sewing_rejection;
                }
            }

            $cutting_rejection = $archived_size_wise_bundle_card->sum('total_rejection');
            $cutting_qty = $archived_size_wise_bundle_card->sum('quantity') - $cutting_rejection;

            $todays_cutting_qty = 0;

            $sizeKey = array_search($sizeId, $sortedSizes) ?? $sizeId;
            $sizeKeyExists = \array_key_exists($sizeKey, $size_wize_report);
            $size_wize_report[$sizeKey]['size_name'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['size_name'] : ($archived_size_wise_bundle_card->first()->size->name ?? 'Size');
            $size_wize_report[$sizeKey]['size_wise_order_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['size_wise_order_qty'] : $size_wise_order_qty;
            $size_wize_report[$sizeKey]['todays_cutting_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['todays_cutting_qty'] : $todays_cutting_qty;
            $size_wize_report[$sizeKey]['cutting_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['cutting_qty'] + $cutting_qty : $cutting_qty;
            $size_wize_report[$sizeKey]['cutting_rejection'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['cutting_rejection'] + $cutting_rejection : $cutting_rejection;
            $size_wize_report[$sizeKey]['left_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['left_qty'] + ($size_wise_order_qty - $cutting_qty) : $size_wise_order_qty - $cutting_qty;
            $size_wize_report[$sizeKey]['todays_print_sent_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['todays_print_sent_qty'] : $todays_print_sent_qty;
            $size_wize_report[$sizeKey]['print_sent_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['print_sent_qty'] + $print_sent_qty : $print_sent_qty;
            $size_wize_report[$sizeKey]['todays_print_received_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['todays_print_received_qty'] : $todays_print_received_qty;
            $size_wize_report[$sizeKey]['print_received_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['print_received_qty'] + $print_received_qty : $print_received_qty;
            $size_wize_report[$sizeKey]['print_rejection_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['print_rejection_qty'] + $print_rejection_qty : $print_rejection_qty;
            $size_wize_report[$sizeKey]['todays_embr_sent_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['todays_embr_sent_qty'] : $todays_embr_sent_qty;
            $size_wize_report[$sizeKey]['embr_sent_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['embr_sent_qty'] + $embr_sent_qty : $embr_sent_qty;
            $size_wize_report[$sizeKey]['todays_embr_received'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['todays_embr_received'] : $todays_embr_received_qty;
            $size_wize_report[$sizeKey]['embr_received_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['embr_received_qty'] + $embr_received_qty : $embr_received_qty;
            $size_wize_report[$sizeKey]['embr_rejection_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['embr_rejection_qty'] + $embr_rejection_qty : $embr_rejection_qty;
            $size_wize_report[$sizeKey]['todays_input_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['todays_input_qty'] : $todays_input_qty;
            $size_wize_report[$sizeKey]['input_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['input_qty'] + $input_qty : $input_qty;
            $size_wize_report[$sizeKey]['todays_output_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['todays_output_qty'] : $todays_output_qty;
            $size_wize_report[$sizeKey]['output_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['output_qty'] + $output_qty : $output_qty;
            $size_wize_report[$sizeKey]['sewing_rejection_qty'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['sewing_rejection_qty'] + $sewing_rejection_qty : $sewing_rejection_qty;
            $size_wize_report[$sizeKey]['sewing_balance'] = $sizeKeyExists ? $size_wize_report[$sizeKey]['sewing_balance'] + ($input_qty - $output_qty - $sewing_rejection_qty) : $input_qty - $output_qty - $sewing_rejection_qty;
        }
        $size_wize_input_data = collect($size_wize_report);
        $size_wise_total_input_data = $this->sizeWiseTotalInputData($size_wize_input_data);

        return [
            'size_wize_input_data' => $size_wize_input_data,
            'size_wise_total_input_data' => $size_wise_total_input_data
        ];
    }

    private function sizeWiseTotalInputData($size_wize_input_data)
    {
        $total_data = [
            'total_size_wise_order_qty' => $size_wize_input_data->sum('size_wise_order_qty'),
            'total_todays_cutting' => $size_wize_input_data->sum('todays_cutting_qty'),
            'total_cutting' => $size_wize_input_data->sum('cutting_qty'),
            'total_cutting_rejection' => $size_wize_input_data->sum('cutting_rejection'),
            'total_left_qty' => $size_wize_input_data->sum('left_qty'),
            'total_todays_print_sent_qty' => $size_wize_input_data->sum('today_print_sent_qty'),
            'total_print_sent_qty' => $size_wize_input_data->sum('print_sent_qty'),
            'total_todays_received' => $size_wize_input_data->sum('todays_received'),
            'total_print_received_qty' => $size_wize_input_data->sum('print_received_qty'),
            'total_print_rejection' => $size_wize_input_data->sum('print_rejection_qty'),
            'total_todays_embroidary_sent' => $size_wize_input_data->sum('todays_embr_sent'),
            'total_embr_sent' => $size_wize_input_data->sum('embr_sent_qty'),
            'total_todays_embr_received' => $size_wize_input_data->sum('todays_embr_received'),
            'total_embr_received' => $size_wize_input_data->sum('embr_received_qty'),
            'total_embr_rejection' => $size_wize_input_data->sum('embr_rejection_qty'),
            'total_todays_input' => $size_wize_input_data->sum('todays_input_qty'),
            'total_input' => $size_wize_input_data->sum('input_qty'),
            'total_todays_sewing_output' => $size_wize_input_data->sum('todays_output_qty'),
            'total_sewing_output' => $size_wize_input_data->sum('output_qty'),
            'total_sewing_rejection' => $size_wize_input_data->sum('sewing_rejection_qty'),
            'total_sewing_balance' => $size_wize_input_data->sum('sewing_balance')
        ];

        return $total_data;
    }

    public function getSizeWiseInputReportDownload($type, $order_id, $po_id, $color_id, $garments_item_id)
    {
        $data = $this->getSizeWiseInputReportData($order_id, $po_id, $color_id, $garments_item_id);
        $order_query = Order::where('id', $order_id)->first();
        $buyer = $order_query->buyer->name ?? '';
        $style = $order_query->style_name ?? '';
        $booking_no = $order_query->reference_no ?? '';

        $purchase_order_query = $po_id && $po_id != 'all' ? PurchaseOrder::where('id', $po_id)->first() : null;

        $po_no = $purchase_order_query ? $purchase_order_query->po_no : 'ALL';

        $color = Color::query()->find($color_id)->name;

        $report_head = [
            'booking_no' => $booking_no,
            'po_no' => $po_no,
            'buyer' => $buyer,
            'style' => $style,
            'color' => $color,
        ];

        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('inputdroplets::reports.downloads.pdf.color-size-wise-sewing-input-report-download', $data, $report_head)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('size-wise-sewing-input-report-'.now()->toDateString().'.pdf');
        } else {
            return \Excel::download(new SizeWiseInputReportExport($data, $report_head), 'size-wise-sewing-input-report-'.now()->toDateString().'.xlsx');
        }
    }

    public function orderWiseSewingInputReport(Request $request)
    {
        $order_id = $request->order_id ?? null;
        $orders = $order_id ? Order::query()->where('id', $order_id)->pluck('style_name', 'id') : [];
        $reports = $this->getOrderWiseSewingInputReportData($order_id);

        return view('inputdroplets::reports.order_sewing_input_report', [
            'reports' => $reports,
            'orders' => $orders,
        ]);
    }

    private function getOrderWiseSewingInputReportData($order_id = null)
    {
        return TotalProductionReport::query()
            ->selectRaw('buyer_id, order_id, garments_item_id, purchase_order_id, sum(total_input) as total_input')
            ->when($order_id, function($query) use($order_id) {
                $query->where('order_id', $order_id);
            })
            ->where('total_input', '>', 0)
            ->groupBy('buyer_id', 'order_id', 'garments_item_id', 'purchase_order_id')
            ->paginate(15);
    }

    public function orderWiseSewingInputReportDownload(Request $request)
    {
        $type = $request->type;
        $order_id = $request->order_id ?? null;
        $data['reports'] = $this->getOrderWiseSewingInputReportData($order_id);

        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('inputdroplets::reports.downloads.pdf.order_sewing_input_report_download', $data)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('order-wise-sewing-input-report-'.now()->toDateString().'.pdf');
        } else {
            return \Excel::download(new OrderWiseInputReportExport($data), 'order-wise-sewing-input-report-'.now()->toDateString().'.xlsx');
        }
    }
}
