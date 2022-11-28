<?php

namespace SkylarkSoft\GoRMG\Misdroplets\Controllers;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Misdroplets\Exports\AuditReportExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Excel, Exception;


class AuditController extends Controller
{
    public function auditReport(Request $request)
    {
        try {
            $buyers = Buyer::pluck('name', 'id')->all();
            $orders = null;
            $reports = null;
            $buyer_id = $request->buyer_id ?? null;
            $order_id = $request->order_id ?? null;
            $from_date = $request->from_date ?? null;
            $to_date = $request->to_date ?? null;
            
            if (isset($request->buyer_id)) {
                $orders = Order::where('buyer_id', $buyer_id)->pluck('style_name', 'id');
            }
            $reports = $this->getAuditReportData($buyer_id, $order_id, $from_date, $to_date);

            return view('misdroplets::reports.audit_report', [
                'buyers' => $buyers,
                'orders' => $orders,
                'reports' => $reports,
                'buyer_id' => $buyer_id,
                'order_id' => $order_id,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'search_data' => $request->all(),
                'print' => 1,
            ]);
        } catch (Exception $e) {
            return redirect('/');
        }
    }

    private function getAuditReportData($buyer_id = '', $order_id = '', $from_date = '', $to_date = '')
    {
        $to_date = $to_date == '' ? date('Y-m-d') : $to_date;

        $reports = DateAndColorWiseProduction::withoutGlobalScope('factoryId')
            ->with('buyer:id,name', 'order:id,style_name', 'order.purchaseOrders')
            ->leftJoin('buyers', 'buyers.id', 'date_and_color_wise_productions.buyer_id')
            ->leftJoin('orders', 'orders.id', 'date_and_color_wise_productions.order_id')
            ->selectRaw('date_and_color_wise_productions.buyer_id, date_and_color_wise_productions.order_id, 
                    SUM(date_and_color_wise_productions.cutting_qty) - SUM(date_and_color_wise_productions.cutting_rejection_qty) as cutting_qty_sum,
                    SUM(date_and_color_wise_productions.cutting_rejection_qty) as cutting_rejection_qty_sum,
                    SUM(date_and_color_wise_productions.print_sent_qty) as print_sent_qty_sum,
                    SUM(date_and_color_wise_productions.print_received_qty) as print_received_qty_sum,
                    SUM(date_and_color_wise_productions.print_rejection_qty) as print_rejection_qty_sum,
                    SUM(date_and_color_wise_productions.embroidary_sent_qty) as embroidary_sent_qty_sum,
                    SUM(date_and_color_wise_productions.embroidary_received_qty) as embroidary_received_qty_sum,
                    SUM(date_and_color_wise_productions.embroidary_rejection_qty) as embroidary_rejection_qty_sum,
                    SUM(date_and_color_wise_productions.input_qty) as input_qty_sum,
                    SUM(date_and_color_wise_productions.sewing_output_qty) as sewing_output_qty_sum,
                    SUM(date_and_color_wise_productions.sewing_rejection_qty) as sewing_rejection_qty_sum,
                    SUM(date_and_color_wise_productions.poly_qty) as poly_qty_sum,
                    SUM(date_and_color_wise_productions.poly_rejection) as finishing_rejection_qty_sum
                ')
            ->when($order_id != '', function ($query) use ($order_id) {
                $query->where('date_and_color_wise_productions.order_id', $order_id);
            })
            ->when(($buyer_id != '' && $order_id == ''), function ($query) use ($buyer_id) {
                $query->where('date_and_color_wise_productions.buyer_id', $buyer_id);
            })
            ->when($from_date != '', function ($query) use ($from_date) {
                $query->whereDate('date_and_color_wise_productions.production_date', '>=', $from_date);
            })
            ->whereDate('date_and_color_wise_productions.production_date', '<=', $to_date)
            ->where('date_and_color_wise_productions.factory_id', factoryId())
            ->whereNull('orders.deleted_at')
            ->whereNull('buyers.deleted_at')
            ->groupBy('date_and_color_wise_productions.buyer_id', 'date_and_color_wise_productions.order_id')
            ->orderBy('date_and_color_wise_productions.buyer_id', 'desc')
            ->orderBy('date_and_color_wise_productions.order_id')
            ->paginate(15);

        return $reports;
    }

    public function auditReportDownload($type, Request $request)
    {
        $buyer_id = $request->buyer_id ?? '';
        $order_id = $request->order_id ?? '';
        $from_date = $request->from_date ?? '';
        $to_date = $request->to_date ?? date('Y-m-d');
        $current_page = $request->current_page;
        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($current_page) {
            return $current_page;
        });
        $data['reports'] = $this->getAuditReportData($buyer_id, $order_id, $from_date, $to_date);
        $data['buyer_id'] = $buyer_id;
        $data['order_id'] = $order_id;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['order_info'] = Order::where('id', $order_id)->first();
        $data['buyer'] = Buyer::where('id', $buyer_id)->first();       
        $data['print'] = 0;
        if ($type == 'pdf') {
            $pdf = \PDF::loadView('misdroplets::reports.downloads.pdf.audit_report', $data, [], [
                'format' => 'A4-L'
            ]);
            return $pdf->setPaper('A4', 'landscape')->download('audit-report.pdf');
        } else {
            return \Excel::download(new AuditReportExport($data), 'Audit report.xlsx');
        }
    }    
}
