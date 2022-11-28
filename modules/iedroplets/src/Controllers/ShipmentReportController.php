<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use SkylarkSoft\GoRMG\Iedroplets\Export\AllOrdersShipmentSummaryReportExcel;
use SkylarkSoft\GoRMG\Iedroplets\Models\Shipment;
use SkylarkSoft\GoRMG\Iedroplets\PackageConst;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class ShipmentReportController extends Controller
{
    public function getAllOrdersShipmentSummary(Request $request)
    {
        $order_id = $request->get('order_id');

        $orders = Order::query()
            ->when($order_id, function ($query) use ($order_id) {
                return $query->where('id', '>=', $order_id);
            })
            ->pluck('style_name', 'id')
            ->paginate(30);

        $shipments = Shipment::with(
            [
                'order:id,style_name',
                'buyer:id,name',
                'purchaseOrder:id,po_quantity,po_no,ex_factory_date'
            ])
            ->when($order_id, function ($query) use ($order_id) {
                $query->where('order_id', $order_id);
            })->paginate(100);


        $data = [
            'shipments' => $shipments,
            'orders' => $orders,
            'order_id' => $order_id,
        ];

        return view(PackageConst::PACKAGE_NAME . '::reports.order_wise_shipment_report', $data);
    }

    public function getAllOrdersShipmentSummaryReportData($page, $order_id = '')
    {
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        return Shipment::with([
            'order:id,booking_no',
            'buyer:id,name',
            'purchaseOrder:id,po_quantity,po_no,inspection_date,unit_price'
        ])->when($order_id, function ($q) use ($order_id) {
            return $q->where('order_id', $order_id);
        })->paginate(100);
    }

    public function getAllOrdersShipmentSummaryReportDownload(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $type = $request->get('type');
        $order_id = $request->get('order_id');
        $page = $request->get('page');
        $data['shipments'] = $this->getAllOrdersShipmentSummaryReportData($page, $order_id);
        $data['type'] = $type;

        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView(PackageConst::PACKAGE_NAME . '::reports.downloads.pdf.all-orders-shipment-summary-report-download', $data)
                ->setPaper('A4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('orders-shipment-summary-report.pdf');
        } else {
            return \Excel::download(new AllOrdersShipmentSummaryReportExcel($data),
                'all-orders-shipment-summary-report-download.xlsx');
        }
    }

    public function getBuyerShipment()
    {
        $buyers = Buyer::pluck('name', 'id')->all();
        return view(PackageConst::PACKAGE_NAME . '::reports.buyer_wise_shipment_report')
            ->with('buyers', $buyers);
    }

    public function getBuyerWiseShipmentReport($buyer_id): array
    {
        $buyer_wise_shipment = Shipment::with(['buyer', 'order', 'purchaseOrder', 'color'])
            ->where('buyer_id', $buyer_id)
            ->get()
            ->groupBy('purchase_order_id')
            ->map(function ($shipments) {

                $color_unique = $shipments->first();
                $color_order_qty = PurchaseOrderDetail::where([
                    'purchase_order_id' => $color_unique->purchase_order_id,
                ])->sum('quantity');

                $shipment_qty = $shipments->sum('ship_quantity');
                $shipment_balance_qty = $color_order_qty - $shipment_qty;
                $sewing_balance_qty = ($shipment_balance_qty > 0) ? $shipment_balance_qty + ($shipment_balance_qty * 3) / 100 : 0;
                $total_export_value = $color_unique->purchaseOrder->unit_price * $color_order_qty;
                $total_shipout_value = $shipment_qty * $color_unique->purchaseOrder->unit_price;
                $total_export_balance = $color_unique->purchaseOrder->unit_price * $color_unique->purchaseOrder->po_quantity - $total_shipout_value;

                return [
                    'buyer' => $color_unique->buyer->name ?? '',
                    'style_name' => $color_unique->order->style_name ?? '',
                    'order' => $color_unique->purchaseOrder->po_no ?? '',
                    'order_qty' => $color_order_qty ?? '',
                    'shipment_qty' => $shipment_qty,
                    'shipment_balance_qty' => $shipment_balance_qty,
                    'sewing_balance_qty' => (int)$sewing_balance_qty,
                    'shipment_date' => $color_unique->purchaseOrder->ex_factory_date ?? '',
                    'total_export_value' => $total_export_value,
                    'total_shipout_value' => $total_shipout_value,
                    'total_export_balance' => $total_export_balance,
                ];
            });


        return [
            'buyer_wise_shipment' => $buyer_wise_shipment,
            'total_rows' => [
                'total_color_order_qty' => $buyer_wise_shipment->sum('order_qty') ?? 0,
                'total_shipment_qty' => $buyer_wise_shipment->sum('shipment_qty') ?? 0,
                'total_shipment_balance_qty' => $buyer_wise_shipment->sum('shipment_balance_qty') ?? 0,
                'total_sewing_balance_qty' => $buyer_wise_shipment->sum('sewing_balance_qty') ?? 0,
                'total_total_export_value' => $buyer_wise_shipment->sum('total_export_value') ?? 0,
                'total_total_shipout_value' => $buyer_wise_shipment->sum('total_shipout_value') ?? 0,
                'total_total_export_balance' => $buyer_wise_shipment->sum('total_export_balance') ?? 0,
            ]
        ];
    }

    public function getBuyerWiseShipmentReportDownload($type, $buyer_id)
    {
        $data = $this->getBuyerWiseShipmentReport($buyer_id);
        $buyer['buyer'] = Buyer::where('id', $buyer_id)->first()->name ?? '';
        if ($type == 'pdf') {
            $pdf = \PDF::loadView(PackageConst::PACKAGE_NAME . '::reports.downloads.pdf.buyer-wise-shipment-report-download', $data,
                $buyer, [], [
                    'format' => 'A4-L'
                ]);
            return $pdf->download('buyer-wise-shipment-report.pdf');
        } else {
            \Excel::create('Buyer Wise Shipment Report', function ($excel) use ($data, $buyer) {
                $excel->sheet('Buyer Wise Shipment Report', function ($sheet) use ($data, $buyer) {
                    $sheet->loadView(PackageConst::PACKAGE_NAME . '::reports.downloads.excels.buyer-wise-shipment-report-download',
                        $data, $buyer);
                });
            })->export('xls');
        }
    }


}
