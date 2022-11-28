<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Controllers;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Iedroplets\Export\ShipmentReportExport;
use SkylarkSoft\GoRMG\Iedroplets\Models\Shipment;
use SkylarkSoft\GoRMG\Iedroplets\PackageConst;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class ShipmentReportControllerExt
{
    public function dailyShipment(Request $request)
    {
        $startDate = $request->get('start_date', now()->toDateString());
        $endDate = $request->get('end_date');
        $buyerId = $request->get('buyer_id');
        $orderId = $request->get('order_id');
        $buyers = $this->getBuyers();
        $orders_list = $this->getOrders($buyerId);

        $shipments = $this->getShipments($orderId, $startDate, $endDate);

        $downloadURL = "daily-shipment-report?start_date={$startDate}&end_date={$endDate}&order_id={$orderId}";

        $data = compact(['shipments', 'buyers', 'startDate', 'endDate', 'orderId', 'downloadURL', 'orders_list']);

        $request->flash();

        $data['view'] = true;

        if ($request->has('pdf')) {
            $pdf = \PDF::loadView(PackageConst::PACKAGE_NAME . '::reports.downloads.pdf.shipment_report_download', $data);
            $pdf->setPaper('a4', 'landscape');
            return $pdf->download('daily-shipment-report.pdf');
        }

        if ($request->has('xls')) {
            return \Excel::download(new ShipmentReportExport($data), 'daily-shipment-report.xlsx');
        }

        return view(PackageConst::PACKAGE_NAME . '::reports.daily_shipment_report', $data);
    }

    public function overallShipment(Request $request)
    {
        $startDate = $request->get('start_date', now()->toDateString());
        $endDate = $request->get('end_date');
        $orderId = $request->get('order_id');
        $buyerId = $request->get('buyer_id');
        $buyers = $this->getBuyers();
        $orders_list = $this->getOrders($buyerId);

        $shipments = $this->getShipments($orderId, $startDate, $endDate);

        $downloadURL = "overall-shipment-report?start_date={$startDate}&end_date={$endDate}&order_id={$orderId}";

        $data = compact(['shipments', 'buyers', 'startDate', 'endDate', 'orderId', 'downloadURL', 'orders_list']);

        $request->flash();

        $data['view'] = true;

        if ($request->has('pdf')) {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView(PackageConst::PACKAGE_NAME . '::reports.downloads.pdf.overall_shipment_report_download', $data)
                ->setPaper('a4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('overall-shipment-report.pdf');
        }

        if ($request->has('xls')) {
            return \Excel::download(new ShipmentReportExport($data), 'overall-shipment-report.xlsx');
        }

        return view(PackageConst::PACKAGE_NAME . '::reports.overall_shipment_report', $data);
    }

    private function getBuyers(): \Illuminate\Support\Collection
    {
        return Buyer::pluck('name', 'id');
    }

    private function getShipments($orderId, $startDate, $endDate)
    {
        return Shipment::with([
            'purchaseOrder:id,po_no,po_quantity,ex_factory_date',
            'buyer:id,name',
            'order:id,style_name'
        ])
            ->where('ship_quantity', '!=', 0)
            ->when($orderId, function ($query) use ($orderId) {
                $query->where('order_id', $orderId);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate);
            }, function ($query) use ($startDate) {
                $query->whereDate('created_at', $startDate);
            })
            ->get();
    }

    private function getOrders($buyerId = null)
    {
        return $buyerId ?
            Order::query()
                ->where('buyer_id', $buyerId)
                ->pluck('style_name', 'id') :
            [];
    }
}
