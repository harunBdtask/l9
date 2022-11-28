<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use PDF, Excel;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\V2\OrderWiseCuttingReportExport;

class OrderWiseReportController extends Controller
{
    public function getAllOrderReport(Request $request)
    {
        $order_id = $request->order_id ?? null;
        $orders = [];
        if ($order_id) {
            $orders = Order::where('id', $order_id)->pluck('style_name', 'id');
        }
        $reports = $this->getOrderWiseData($order_id);

        return view('cuttingdroplets::reports.v2.all_orders_report', [
            'orders' => $orders,
            'reports' => $reports
        ]);
    }

    public function getOrderWiseData($order_id = '')
    {
        return TotalProductionReport::query()
            ->with([
                'buyer:id,name',
                'order:id,style_name',
                'purchaseOrder:id,po_no,po_quantity,po_pc_quantity,ex_factory_date',
            ])
            ->selectRaw('
            buyer_id,
            order_id,
            garments_item_id,
            purchase_order_id,
            SUM(total_cutting - total_cutting_rejection) as total_cutting
            ')
            ->when($order_id != '', function ($query) use ($order_id) {
                return $query->where('order_id', $order_id);
            })
            ->groupBy('buyer_id', 'order_id', 'garments_item_id', 'purchase_order_id')
            ->orderBy('buyer_id', 'desc')
            ->paginate(30);
    }

    public function allOrdersCuttingReportDownload(Request $request)
    {
        $type = $request->type;
        $page = $request->page ?? 1;
        $order_id = $request->order_id ?? null;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $data['reports'] = $this->getOrderWiseData($order_id);
        $data['type'] = $type;
        if ($type == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('cuttingdroplets::reports.downloads.pdf.v2.all-orders-cutting-report', $data)
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('order-wise-cutting-report'. date('d_m_Y') .'.pdf');
        } else {
            return Excel::download(new OrderWiseCuttingReportExport($data), 'order-wise-cutting-report'. date('d_m_Y') .'.xlsx');
        }
    }
}