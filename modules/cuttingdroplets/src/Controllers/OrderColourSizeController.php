<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use PDF;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\AllOrdersCuttingReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\BuyerBookingWiseConsumptionReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\BuyerWiseCuttingReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\ColorWiseCuttingReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\DailyFabricConsumptionReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\ExcessCuttingReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\MonthlyFabricConsumptionReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\OrderWiseCuttingReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\PoWiseCuttingReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;

class OrderColourSizeController extends Controller
{

    public function getAllOrderReport(Request $request)
    {
        $order_id = $request->order_id ?? null;
        $orders = [];
        if ($order_id) {
            $orders = Order::where('id', $order_id)->pluck('style_name', 'id');
        }
        $from_date = $request->from_date ?? null;
        $to_date = $request->to_date ?? null;

        if ($from_date && $to_date) {
            $carbon_from_date = Carbon::parse($from_date);
            $carbon_to_date = Carbon::parse($to_date);
            if ($carbon_to_date->diffInDays($carbon_from_date) > 184) {
                \Session::flash('alert-danger', 'Please give max 6 months date range!');
                return redirect('/all-orders-cutting-report');
            }
        }
        $reports = $this->getOrderWiseData($order_id, $from_date, $to_date);

        return view('cuttingdroplets::reports.all_orders_report', [
            'orders' => $orders,
            'reports' => $reports
        ]);
    }

    public function getOrderWiseData($order_id = '', $from_date = '', $to_date = '')
    {
        $total_production_report = TotalProductionReport::query()
            ->with([
                'buyer:id,name',
                'order:id,style_name',
                'purchaseOrder:id,po_no,po_quantity',
                'color:id,name',
            ])
            ->selectRaw('
            buyer_id,
            order_id,
            purchase_order_id,
            color_id,
            SUM(todays_cutting) as todays_cutting_sum,
            SUM(todays_cutting_rejection) as todays_cutting_rejection_sum,
            SUM(total_cutting) as total_cutting_sum,
            SUM(total_cutting_rejection) as total_cutting_rejection_sum,
            SUM(total_sent) as total_sent_sum,
            SUM(total_embroidary_sent) as total_embroidary_sent_sum,
            SUM(total_received) as total_received_sum,
            SUM(total_embroidary_received) as total_embroidary_received_sum,
            SUM(total_input) as total_input_sum,
            SUM(total_sewing_output) as total_output_sum
            ')
            ->when($order_id != '', function ($query) use ($order_id) {
                return $query->where('order_id', $order_id);
            })
            ->when($order_id == '' && $from_date == '' && $to_date == '', function ($query) {
                return $query->whereDate('created_at', '>=', now()->subDays(184)->toDateString());
            })
            ->when($order_id == '' && $from_date != '' && $to_date != '', function ($query) use ($from_date, $to_date) {
                return $query->whereDate('created_at', '>=', $from_date)
                    ->whereDate('created_at', '<=', $to_date);
            })
            ->groupBy('buyer_id', 'order_id', 'purchase_order_id', 'color_id')
            ->orderBy('buyer_id', 'desc')
            ->paginate(30);

        return $total_production_report;
    }

    public function allOrdersCuttingReportDownload(Request $request)
    {
        $type = $request->type;
        $page = $request->page ?? 1;
        $order_id = $request->order_id ?? null;
        $from_date = $request->from_date ?? null;
        $to_date = $request->to_date ?? null;

        if ($type == 'pdf') {
            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
            $data['reports'] = $this->getOrderWiseData($order_id, $from_date, $to_date);
            $data['type'] = $type;
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('cuttingdroplets::reports.downloads.pdf.all-orders-cutting-report', $data)
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('all-orders-cutting-report.pdf');
        } else {
            return \Excel::download(new AllOrdersCuttingReportExport($order_id, $from_date, $to_date), 'all-orders-cutting-report.xlsx');
        }
    }

    public function getBuyerWiseReport()
    {
        return view('cuttingdroplets::reports.buyer_wise_report');
    }

    public function getBuyerWiseReportData(Request $request)
    {
        $buyer_id = $request->buyer_id;
        $from_date = $request->from_date ?? '';
        $to_date = $request->to_date ?? '';

        if ($from_date && $to_date) {
            $carbon_from_date = Carbon::parse($from_date);
            $carbon_to_date = Carbon::parse($to_date);
            if ($carbon_to_date->diffInDays($carbon_from_date) > 186) {
                $html = '';
                $order_info_data = null;
                $html .= '<tr class="tr-height">';
                $html .= '<td colspan="8" class="text-danger">No Data</td>';
                $html .= '</tr>';
                $status = 500;
                return response()->json(['status' => $status, 'html' => $html, 'buyer_id' => $buyer_id, 'to_date' => 'Please give six month date range.']);
            }
        }
        $total_production_report_query = TotalProductionReport::query()
            ->with([
                'order:id,style_name',
                'purchaseOrder:id,po_no,po_quantity',
            ])
            ->selectRaw('order_id, purchase_order_id,
            sum(total_production_reports.todays_cutting) as todays_cutting,
            sum(total_production_reports.total_cutting) as total_cutting,
            sum(total_production_reports.todays_cutting_rejection) as todays_cutting_rejection,
            sum(total_production_reports.total_cutting_rejection) as total_cutting_rejection')
            ->when(($from_date == '' || $to_date == ''), function ($query) {
                $date = now()->subDays(186)->toDateString();
                $query->whereDate('total_production_reports.created_at', '>=', $date);
            })
            ->when(($from_date != '' && $to_date != ''), function ($query) use ($from_date, $to_date) {
                $query->whereDate('total_production_reports.created_at', '>=', $from_date)
                    ->whereDate('total_production_reports.created_at', '<=', $to_date);
            })
            ->where('buyer_id', $buyer_id)
            ->orderBy('order_id', 'desc')
            ->orderBy('purchase_order_id', 'desc')
            ->groupBy('order_id', 'purchase_order_id');

        if ($total_production_report_query->count() > 0) {
            $buyer_wise_report_html = '';
            $buyer_wise_report_html .= '<tr class="tr-height">';
            $buyer_wise_report_html .= '<td colspan="8" class="text-danger">No Data</td>';
            $buyer_wise_report_html .= '</tr>';
            if ($request->ajax()) {
                $print = 0; // No Print
                $total_production_report = $total_production_report_query->paginate(18);
                $buyer_wise_report_html = view('cuttingdroplets::reports.includes.buyer_wise_report_inc', array('total_production_report' => $total_production_report, 'print' => $print))->render();
            }
            $status = 200;
            return response()->json(['status' => $status, 'html' => $buyer_wise_report_html, 'buyer_id' => $buyer_id, 'to_date' => '']);
        } else {
            $html = '';
            $order_info_data = null;
            $html .= '<tr class="tr-height">';
            $html .= '<td colspan="8" class="text-danger">No Data</td>';
            $html .= '</tr>';
            $status = 500;
            return response()->json(['status' => $status, 'html' => $html, 'buyer_id' => $buyer_id, 'to_date' => '']);
        }
    }

    public function getBuyerWiseReportDownload()
    {
        $type = request('type');
        $buyer_id = request('buyer_id') ?? '';
        $from_date = request('from_date') ?? '';
        $to_date = request('to_date') ?? '';
        $page = request('page') ?? 1;
        $request = request()->all();
        if ($type == 'pdf') {
            $total_production_report = $this->getBuyerWiseReportDataForDownload($buyer_id, $from_date, $to_date, $page);
            $buyer_name = Buyer::where('id', $buyer_id)->first()->name ?? '';
            $print = 1;
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView(
                    'cuttingdroplets::reports.downloads.pdf.buyer-wise-download',
                    compact('total_production_report', 'print', 'buyer_name')
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('buyer-wise-cutting-report-' . date('d-m-Y') . '.pdf');
        } else {
            return \Excel::download(new BuyerWiseCuttingReportExport($request), 'buyer-wise-cutting-report-' . date('d-m-Y') . '.xlsx');
        }
    }

    private function getBuyerWiseReportDataForDownload($buyer_id, $from_date = '', $to_date = '', $page)
    {
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $total_production_report = TotalProductionReport::query()
            ->with([
                'order:id,style_name',
                'purchaseOrder:id,po_no,po_quantity',
            ])
            ->selectRaw('order_id, purchase_order_id,
            sum(total_production_reports.todays_cutting) as todays_cutting,
            sum(total_production_reports.total_cutting) as total_cutting,
            sum(total_production_reports.todays_cutting_rejection) as todays_cutting_rejection,
            sum(total_production_reports.total_cutting_rejection) as total_cutting_rejection')
            ->when(($from_date == '' || $to_date == ''), function ($query) {
                $date = now()->subDays(186)->toDateString();
                $query->whereDate('total_production_reports.created_at', '>=', $date);
            })
            ->when(($from_date != '' && $to_date != ''), function ($query) use ($from_date, $to_date) {
                $query->whereDate('total_production_reports.created_at', '>=', $from_date)
                    ->whereDate('total_production_reports.created_at', '<=', $to_date);
            })
            ->where('buyer_id', $buyer_id)
            ->orderBy('order_id', 'desc')
            ->orderBy('purchase_order_id', 'desc')
            ->groupBy('order_id', 'purchase_order_id')
            ->paginate(18);

        return $total_production_report;
    }

    public function getColorWiseSummaryReport()
    {
        $buyers = Buyer::pluck('name', 'id')->all();

        return view('cuttingdroplets::reports.color_wise_summary')->with('buyers', $buyers);
    }

    public function getColorWiseSummaryReportData($purchase_order_id, $color_id)
    {
        $result_data = [];
        $bundle_cards = BundleCard::with([
            'buyer:id,name',
            'order:id,booking_no,order_style_no',
            'purchaseOrder:id,po_no,po_quantity',
            'color:id,name',
            'size:id,name'
        ])
            ->where([
                'purchase_order_id' => $purchase_order_id,
                'color_id' => $color_id,
                'status' => 1
            ])->get();

        $size_wize_report = [];
        foreach ($bundle_cards->groupBy('size_id') as $key => $size_wise_bundles) {
            $unique_size = $size_wise_bundles->first();
            $size_wize_report[$key]['size_name'] = $unique_size->size->name ?? '';

            $size_order_qty = PurchaseOrderDetail::where([
                'purchase_order_id' => $purchase_order_id,
                'color_id' => $color_id,
                'size_id' => $unique_size->size_id
            ])->first()->quantity ?? 0;

            $cutting_qty = $size_wise_bundles->sum('quantity');
            $size_wize_report[$key]['cutting_qty'] = $cutting_qty;
            $size_wize_report[$key]['size_order_qty'] = $size_order_qty;

            $left_qty = $size_order_qty - $cutting_qty;
            $size_wize_report[$key]['left_qty'] = $left_qty;

            $extra_qty = 0;
            if ($cutting_qty > 0 && $size_order_qty > 0) {
                $extra_qty = ($cutting_qty > $size_order_qty) ? number_format(((($cutting_qty - $size_order_qty) * 100) / $size_order_qty), 2) : 0;
            }

            $size_wize_report[$key]['extra_qty'] = $extra_qty;
        }
        /*
		// table wise report
		$result_report = [];
		foreach ($bundle_cards->groupBy('cutting_no') as $key_cutting_no => $bundles) {
			$unique_cutting = $bundles->first();
			$result_report[$key_cutting_no]['buyer'] = $unique_cutting->order->buyer->name;
			$result_report[$key_cutting_no]['order'] = $unique_cutting->order->order_no;
			$result_report[$key_cutting_no]['style'] = $unique_cutting->order->style->name;
			$result_report[$key_cutting_no]['color'] = $unique_cutting->color->name;
			$result_report[$key_cutting_no]['cutting_table_no'] = $unique_cutting->cuttingTable->table_no ?? '';
			$result_report[$key_cutting_no]['cutting_no'] = $unique_cutting->cutting_no;
			$result_report[$key_cutting_no]['bundle_quantity'] = count($bundles);
			$result_report[$key_cutting_no]['cutting_quantity'] = $bundles->sum('quantity') - $bundles->sum('total_rejection');
			$result_report[$key_cutting_no]['cutting_date'] = date("jS F, Y", strtotime($unique_cutting->created_at));
		}

		$result_data['result_report'] = $result_report;
		*/
        $result_data['size_wize_report'] = $size_wize_report;

        return $result_data;
    }

    public function getColorWiseCuttingReportDownload($type, $buyer_id, $purchase_order_id, $color_id)
    {
        $result_data = $this->getColorWiseSummaryReportData($purchase_order_id, $color_id);
        $purchaseOrder = PurchaseOrder::with([
            'buyer:id,name',
            'order:id,order_style_no'
        ])
            ->where('id', $purchase_order_id)
            ->first();

        $report_head = [
            'buyer' => $purchaseOrder->buyer->name ?? '',
            'style' => $purchaseOrder->order->order_style_no ?? '',
            'order_no' => $purchaseOrder->po_no ?? '',
            'color' => Color::where('id', $color_id)->first()->name ?? '',
        ];

        if ($type == 'pdf') {
            $pdf = \PDF::loadView('cuttingdroplets::reports.downloads.pdf.color-wise-cutting-report', $result_data, $report_head, [], [
                'format' => 'A4-L'
            ]);
            return $pdf->download('color-wise-cutting-report.pdf');
        } else {
            \Excel::create('Color Wise Cutting Production Report', function ($excel) use ($result_data, $report_head) {
                $excel->sheet('Cutting Production Report sheet', function ($sheet) use ($result_data, $report_head) {
                    $sheet->loadView('cuttingdroplets::reports.downloads.excels.color-wise-cutting-report', $result_data, $report_head);
                });
            })->export('xls');
        }
    }

    public function excessCuttingData($buyer_id = '', $order_id = '',  $from_date = '', $to_date = '')
    {
        $orders = TotalProductionReport::query()
            ->withoutGlobalScope('factoryId')
            ->selectRaw(
                'total_production_reports.buyer_id,
				total_production_reports.order_id,
				total_production_reports.purchase_order_id,
				sum(total_production_reports.todays_cutting) as todays_cutting,
				sum(total_production_reports.todays_cutting_rejection) as todays_cutting_rejection,
				sum(total_production_reports.total_cutting) as total_cutting,
				sum(total_production_reports.total_cutting_rejection) as total_cutting_rejection
				'
            )
            ->when(($buyer_id == '' && $order_id == '' && ($from_date == '' || $to_date == '')), function ($query) {
                $date = now()->subDays(186)->startOfDay()->toDateTimeString();
                $query->where('total_production_reports.created_at', '>=', $date);
            })
            ->when(($from_date != '' && $to_date != ''), function ($query) use ($from_date, $to_date) {
                $from = Carbon::parse($from_date)->startOfDay()->toDateTimeString();
                $to = Carbon::parse($to_date)->endOfDay()->toDateTimeString();
                $query->where('total_production_reports.created_at', '>=', $from)
                    ->where('total_production_reports.created_at', '<=', $to);
            })
            ->where('total_production_reports.factory_id', factoryId())
            ->when(($buyer_id != '' && $order_id == ''), function ($query) use ($buyer_id) {
                return $query->where('total_production_reports.buyer_id', $buyer_id);
            })
            ->when($order_id != '', function ($query) use ($order_id) {
                return $query->where('total_production_reports.order_id', $order_id);
            })
            ->join('purchase_orders', 'purchase_orders.id', 'total_production_reports.purchase_order_id')
            ->when(($buyer_id == '' || $order_id == ''), function ($query) {
                $query->whereRaw('(total_production_reports.total_cutting - total_production_reports.total_cutting_rejection) >= purchase_orders.po_pc_quantity');
            })
            ->with(['buyer:id,name', 'order:id,style_name', 'purchaseOrder:id,po_no,po_quantity,po_pc_quantity'])
            ->groupBy('total_production_reports.buyer_id', 'total_production_reports.order_id', 'total_production_reports.purchase_order_id')
            ->orderBy('total_production_reports.order_id')
            ->paginate();

        return $orders;
    }

    public function getExcessReport(Request $request)
    {
        $buyer_id = $request->buyer_id ?? '';
        $order_id = $request->order_id ?? '';
        $from_date = $request->from_date ?? '';
        $to_date = $request->to_date ?? '';

        $buyers = $buyer_id ? Buyer::query()->where('id', $buyer_id)->pluck('name', 'id') : [];
        $orders = $order_id ? Order::query()->where('id', $order_id)->pluck('style_name', 'id') : [];

        if ($from_date && $to_date) {
            $carbon_from_date = Carbon::parse($from_date);
            $carbon_to_date = Carbon::parse($to_date);
            if ($carbon_to_date->diffInDays($carbon_from_date) > 184) {
                \Session::flash('alert-danger', 'Please give max 6 months date range!');
                return redirect('/excess-cutting-report');
            }
        }

        $reports = $this->excessCuttingData($buyer_id, $order_id, $from_date, $to_date);

        return view('cuttingdroplets::reports.excess_cutting', [
            'reports' => $reports,
            'buyers' => $buyers,
            'orders' => $orders,
            'buyer_id' => $buyer_id,
            'order_id' => $order_id,
        ]);
    }

    public function excessCuttingReportDownload(Request $request)
    {
        $type = $request->type;
        $page = $request->page ?? 1;
        $buyer_id = $request->buyer_id ?? '';
        $order_id = $request->order_id ?? '';
        $from_date = $request->from_date ?? '';
        $to_date = $request->to_date ?? '';
        if ($type == 'pdf') {
            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
            $reports = $this->excessCuttingData($buyer_id, $order_id, $from_date, $to_date);
            $print = 1;
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('cuttingdroplets::reports.downloads.excess-cutting-download', compact('reports', 'print'))
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);
            return $pdf->download('order-wise-excess-cutting-report-' . date('d-m-Y') . '.pdf');
        } else {
            return \Excel::download(new ExcessCuttingReportExport($request), 'excess-cutting-report-' . date('d-m-Y') . '.xlsx');
        }
    }

    public function getStyleWiseReport($order_id)
    {
        $total_production_report = TotalProductionReport::with([
            'purchaseOrder:id,po_no,po_quantity',
            'color:id,name'
        ])
            ->where('order_id', $order_id)
            ->get();

        $po_dropdown_data = [];
        $report_data = [];
        foreach ($total_production_report->groupBy('purchase_order_id') as $key => $reportByOrder) {

            $singlePo = $reportByOrder->first();
            $report_data[$key]['po'] = $singlePo->purchaseOrder->po_no ?? 'PO';
            $order_total_qty = $singlePo->purchaseOrder->po_quantity ?? 0;
            $report_data[$key]['order_quantity'] = $order_total_qty;

            $po_dropdown_data[$singlePo->purchase_order_id] = $singlePo->purchaseOrder->po_no ?? 'PO';
            $report_data[$key]['todays_cutting'] = $reportByOrder->sum('todays_cutting') - $reportByOrder->sum('todays_cutting_rejection');
            $total_cutting = $reportByOrder->sum('total_cutting') - $reportByOrder->sum('total_cutting_rejection');
            $report_data[$key]['total_cutting'] = $total_cutting;
            $report_data[$key]['left_quantity'] = $order_total_qty - $total_cutting;
        }

        $result['po_dropdown_data'] = $po_dropdown_data;
        $result['report_data'] = $report_data;

        return $result;
    }

    public function getStyleWiseReportDownload($type, $style_id)
    {
        $result_data = $this->getStyleWiseReport($style_id);
        $style_query = Order::where('id', $style_id)->first();
        $style = $style_query->order_style_no;
        $buyer = $style_query->buyer->name;
        if ($type == 'pdf') {
            $pdf = PDF::loadView('cuttingdroplets::reports.downloads.pdf.style-wise-report-download', compact('result_data', 'style', 'buyer'));
            return $pdf->download('order-wise-cutting-report.pdf');
        } else {
            return \Excel::download(new OrderWiseCuttingReportExport($result_data, $style, $buyer), 'order-wise-cutting-report.xlsx');
        }
    }

    public function getOrderWiseReport()
    {
        $buyers = Buyer::pluck('name', 'id')->all();
        return view('cuttingdroplets::reports.order_wise_report')->with('buyers', $buyers);
    }

    public function getOrderWiseReportPost($purchase_order_id)
    {
        $result = [];
        $report_size_wise = [];
        $total_report = [];

        $total_order_cutting = 0;
        $total_today_cutting = 0;
        $total_total_cutting = 0;
        $total_left_qty = 0;

        $order_details = PurchaseOrderDetail::with([
            'color:id,name',
            'size:id,name'
        ])
            ->where('purchase_order_id', $purchase_order_id)
            //->where('quantity', '>', 0)
            ->orderBy('color_id')
            ->select('color_id', 'size_id', 'quantity')
            ->get();

        foreach ($order_details as $key => $color_size) {
            $report_size_wise[$key]['color'] = $color_size->color->name ?? '';
            $report_size_wise[$key]['size'] = $color_size->size->name ?? '';

            $size_wise_bundles = BundleCard::where([
                'purchase_order_id' => $purchase_order_id,
                'color_id' => $color_size->color_id,
                'size_id' => $color_size->size_id,
                'status' => 1
            ]);

            $total_cutting = $size_wise_bundles->sum('quantity') - $size_wise_bundles->sum('total_rejection');
            $report_size_wise[$key]['total_cutting'] = $total_cutting;

            $today_cutting = $size_wise_bundles->where('cutting_date', date('Y-m-d'))->sum('quantity')
                - $size_wise_bundles->where('cutting_date', date('Y-m-d'))->sum('total_rejection');

            $report_size_wise[$key]['today_cutting'] = $today_cutting;
            $report_size_wise[$key]['size_order_qty'] = $color_size->quantity;

            $left_qty = $color_size->quantity - $total_cutting;
            $report_size_wise[$key]['left_qty'] = $left_qty;

            $extra_cutting_ratio = (($total_cutting > $color_size->quantity) && ($color_size->quantity > 0)) ? number_format(($total_cutting - $color_size->quantity) * 100 / $color_size->quantity, 2) : 0;
            $report_size_wise[$key]['extra_cutting_ratio'] = $extra_cutting_ratio;

            $total_order_cutting += $color_size->quantity;
            $total_today_cutting += $today_cutting;
            $total_total_cutting += $total_cutting;
            $total_left_qty += $left_qty;
        }

        $total_report['total_today_cutting'] = $total_today_cutting;
        $total_report['total_total_cutting'] = $total_total_cutting;
        $total_report['total_left_qty'] = $total_left_qty;
        $total_report['total_order_cutting'] = $total_order_cutting;

        $result['report_size_wise'] = $report_size_wise;
        $result['total_report'] = $total_report;

        return $result;
    }

    public function getOrderWiseReportDownload($type, $order_id)
    {
        $result_data = $this->getOrderWiseReportPost($order_id);
        $order_query = PurchaseOrder::where('id', $order_id)->first();
        $order_no = $order_query->po_no;
        $buyer = $order_query->buyer->name ?? '';
        $style = $order_query->order->order_style_no ?? '';
        if ($type == 'pdf') {
            $pdf = PDF::loadView('cuttingdroplets::reports.downloads.order-wise-download', compact('result_data', 'order_no', 'buyer', 'style'));
            return $pdf->download('po-wise-cutting-report.pdf');
        } else {
            return \Excel::download(new PoWiseCuttingReportExport($result_data, $order_no, $buyer, $style), 'po-wise-cutting-report.xlsx');
        }
    }

    public function getOrderColorWiseReportPost($purchase_order_id, $color_id)
    {
        $result = [];
        $report_size_wise = [];
        $total_report = [];

        $total_order_cutting = 0;
        $total_today_cutting = 0;
        $total_total_cutting = 0;
        $total_left_qty = 0;

        $order_details = PurchaseOrderDetail::with([
            'color:id,name',
            'size:id,name'
        ])->where([
            'purchase_order_id' => $purchase_order_id,
            'color_id' => $color_id
        ])->orderBy('color_id')->get();

        foreach ($order_details as $key => $color_size) {
            $report_size_wise[$key]['color'] = $color_size->color->name ?? '';
            $report_size_wise[$key]['size'] = $color_size->size->name ?? '';

            $size_wise_bundles = BundleCard::where([
                'purchase_order_id' => $purchase_order_id,
                'color_id' => $color_size->color_id,
                'size_id' => $color_size->size_id,
                'status' => 1
            ]);

            $total_cutting = $size_wise_bundles->sum('quantity') - $size_wise_bundles->sum('total_rejection');
            $report_size_wise[$key]['total_cutting'] = $total_cutting;

            $today_cutting = $size_wise_bundles->where('cutting_date', date('Y-m-d'))->sum('quantity')
                - $size_wise_bundles->where('cutting_date', date('Y-m-d'))->sum('total_rejection');

            $report_size_wise[$key]['today_cutting'] = $today_cutting;
            $report_size_wise[$key]['size_order_qty'] = $color_size->quantity;

            $left_qty = $color_size->quantity - $total_cutting;
            $report_size_wise[$key]['left_qty'] = $left_qty;

            $extra_cutting_ratio = (($total_cutting > $color_size->quantity) && ($color_size->quantity > 0)) ? number_format(($total_cutting - $color_size->quantity) * 100 / $color_size->quantity, 2) : 0;
            $report_size_wise[$key]['extra_cutting_ratio'] = $extra_cutting_ratio;

            $total_order_cutting += $color_size->quantity;
            $total_today_cutting += $today_cutting;
            $total_total_cutting += $total_cutting;
            $total_left_qty += $left_qty;
        }

        $total_report['total_today_cutting'] = $total_today_cutting;
        $total_report['total_total_cutting'] = $total_total_cutting;
        $total_report['total_left_qty'] = $total_left_qty;
        $total_report['total_order_cutting'] = $total_order_cutting;

        $result['report_size_wise'] = $report_size_wise;
        $result['total_report'] = $total_report;

        return $result;
    }

    public function getOrderColorWiseReportDownload($type, $purchase_order_id, $color_id)
    {
        $result_data = $this->getOrderColorWiseReportPost($purchase_order_id, $color_id);
        $order_query = PurchaseOrder::where('id', $purchase_order_id)->first();
        $buyer = $order_query->buyer->name ?? '';
        $style = $order_query->order->order_style_no ?? '';
        $order_no = $order_query->po_no ?? '';
        if ($type == 'pdf') {
            $pdf = PDF::loadView('cuttingdroplets::reports.downloads.order-wise-download', compact('result_data', 'buyer', 'style', 'order_no'));
            return $pdf->download('order-wise-cutting-report.pdf');
        } else {
            return \Excel::download(new ColorWiseCuttingReportExport($result_data, $buyer, $style, $order_no), 'color-wise-cutting-report.xlsx');
        }
    }

    public function getColoWiseCuttingSummaryForm()
    {
        $buyers = Buyer::pluck('name', 'id')->all();

        return view('reports.cutting-module.color-wise-summary')->with('buyers', $buyers);
    }

    // For TNA
    public static function getOrderWiseActualCuttingDateInfo($order_id)
    {
        $bundleCardQuery = BundleCard::where(['order_id' => $order_id, 'status' => 1]);
        $actual_start = '';
        $actual_end = '';
        $duration = '';
        if ($bundleCardQuery->count()) {
            $order_qty = Order::findOrFail($order_id)->total_quantity;
            $firstBundle = $bundleCardQuery->orderBy('created_at', 'asc')->first();
            $actual_start = date('Y-m-d', strtotime($firstBundle->created_at));
            $bundleCardQueryClone = clone $bundleCardQuery;
            $totalCuttingQty = $bundleCardQueryClone->sum('quantity') - $bundleCardQueryClone->sum('total_rejection');
            $lastBundle = $bundleCardQueryClone->orderBy('created_at', 'desc')->first();
            if ($totalCuttingQty >= $order_qty) {
                $actual_end = date('Y-m-d', strtotime($lastBundle->created_at));
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

    public function buyerStyleWiseFabricConsumptionReport(Request $request)
    {
        $buyer_id = $request->buyer_id ?? null;
        $order_id = $request->order_id ?? null;
        $reports = null;
        $buyers = $buyer_id ? Buyer::where('id', $buyer_id)->pluck('name', 'id') : [];
        $orders = $order_id ? Order::query()->where('id', $order_id)->pluck('style_name', 'id') : [];

        if ($buyer_id && $order_id) {
            $reports = $this->buyerStyleWiseFabricConsumptionReportData($order_id);
        }

        return view('cuttingdroplets::reports.buyer_style_wise_fabric_consumption_report', [
            'reports' => $reports,
            'buyers' => $buyers,
            'orders' => $orders,
            'buyer_id' => $buyer_id,
            'order_id' => $order_id,
        ]);
    }

    private function buyerStyleWiseFabricConsumptionReportData($order_id, $current_page = '')
    {
        return BundleCardGenerationDetail::query()
            ->with([
                'buyer:id,name',
                'order:id,style_name',
            ])
            ->select([
                'sid',
                'buyer_id',
                'order_id',
                'ratios',
                'rolls',
                'booking_consumption',
                'cutting_no',
                'colors',
            ])
            ->where('order_id', $order_id)
            ->where('is_regenerated', 0)
            ->where('is_manual', 0)
            ->paginate();
    }

    public function buyerStyleWiseFabricConsumptionReportDownload(Request $request)
    {
        $type = $request->type;
        $current_page = $request->current_page;
        $buyer_id = $request->buyer_id;
        $order_id = $request->order_id;
        $data['buyer_id'] = $buyer_id;
        $data['order_id'] = $order_id;
        if ($current_page != '') {
            Paginator::currentPageResolver(function () use ($current_page) {
                return $current_page;
            });
        }
        $data['reports'] = $this->buyerStyleWiseFabricConsumptionReportData($order_id);
        if ($type == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('cuttingdroplets::reports.downloads.pdf.buyer_style_wise_fabric_consumption_report_download', $data)
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('buyer-booking-wise-consumption-report.pdf');
        } else {
            return \Excel::download(new BuyerBookingWiseConsumptionReportExport($data), 'buyer-booking-wise-consumption-report.xlsx');
        }
    }

    public function dailyFabricConsumptionReport(Request $request)
    {
        $cutting_date = $request->cutting_date ?? date('Y-m-d');
        $reports = $this->getDailyFabricConsumptionReportData($cutting_date);

        return view('cuttingdroplets::reports.daily_fabric_consumption_report', [
            'reports' => $reports,
            'cutting_date' => $cutting_date,
        ]);
    }

    private function getDailyFabricConsumptionReportData($cutting_date)
    {
        return BundleCard::with('details')
            ->select('bundle_cards.bundle_card_generation_detail_id', 'bundle_cards.cutting_date')
            ->join('bundle_card_generation_details', 'bundle_card_generation_details.id', 'bundle_cards.bundle_card_generation_detail_id')
            ->whereDate('cutting_date', $cutting_date)
            ->where([
                'bundle_card_generation_details.is_regenerated' => 0,
                'bundle_card_generation_details.is_manual' => 0
            ])
            ->groupBy('bundle_cards.bundle_card_generation_detail_id', 'bundle_cards.cutting_date')
            ->get();
    }

    public function dailyFabricConsumptionReportDownload(Request $request)
    {
        $type = $request->type;
        $cutting_date = $request->cutting_date;
        $data['cutting_date'] = $cutting_date;
        $data['reports'] = $this->getDailyFabricConsumptionReportData($cutting_date);
        if ($type == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('cuttingdroplets::reports.downloads.pdf.daily_fabric_consumption_report_download', $data)
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('daily-fabric-consumption-report.pdf');
        } else {
            return \Excel::download(new DailyFabricConsumptionReportExport($data), 'daily-fabric-consumption-report.xlsx');
        }
    }

    public function monthlyFabricConsumptionReport(Request $request)
    {
        $year = $request->year ?? date('Y');
        $month = $request->month ?? (int)date('m');
        $reports = $this->getMonthlyFabricConsumptionReportData($year, $month);

        return view('cuttingdroplets::reports.monthly_fabric_consumption_report', [
            'reports' => $reports,
            'year' => $year,
            'month' => $month,
        ]);
    }

    private function getMonthlyFabricConsumptionReportData($year, $month, $current_page = '')
    {
        if ($current_page != '') {
            Paginator::currentPageResolver(function () use ($current_page) {
                return $current_page;
            });
        }
        $from_date = Carbon::parse('first day of ' . date('F', mktime(0, 0, 0, $month, 10)) . ' ' . $year)->toDateString();
        $last_date = Carbon::parse('last day of ' . date('F', mktime(0, 0, 0, $month, 10)) . ' ' . $year)->toDateString();

        return BundleCard::with('details')
            ->select('bundle_cards.bundle_card_generation_detail_id', 'bundle_cards.cutting_date')
            ->join('bundle_card_generation_details', 'bundle_card_generation_details.id', 'bundle_cards.bundle_card_generation_detail_id')
            ->whereDate('cutting_date', '>=', $from_date)
            ->whereDate('cutting_date', '<=', $last_date)
            ->where([
                'bundle_card_generation_details.is_regenerated' => 0,
                'bundle_card_generation_details.is_manual' => 0
            ])
            ->groupBy('bundle_cards.bundle_card_generation_detail_id', 'bundle_cards.cutting_date')
            ->paginate();
    }

    public function monthlyFabricConsumptionReportDownload(Request $request)
    {
        $type = $request->type;
        $year = $request->year;
        $month = $request->month;
        $current_page = $request->current_page;
        $data['year'] = $year;
        $data['month'] = $month;
        $data['reports'] = $this->getMonthlyFabricConsumptionReportData($year, $month, $current_page);
        if ($type == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('cuttingdroplets::reports.downloads.pdf.monthly_fabric_consumption_report_download', $data)
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('monthly-fabric-consumption-report.pdf');
        } else {
            return \Excel::download(new MonthlyFabricConsumptionReportExport($data), 'monthly-fabric-consumption-report.xlsx');
        }
    }
}
