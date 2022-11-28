<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\AllOrdersInventoryReportExport;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\CuttingNoWiseInventoryChallanReportExport;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\InventoryChallanCountReportExport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use PDF, Excel;

class InventoryController extends Controller
{
    public function getOrderWiseCuttingInventorySummary()
    {
        $order_id = request('order_id') ?? null;
        $orders = $order_id ? Order::query()->where('id', $order_id)->pluck('style_name', 'id') : [];
        $order_wise_report = $this->getOrderWiseCuttingInventorySummaryData(request('order_id'));

        return view('inputdroplets::reports.order_wise_cutting_inventory_summary', [
            'order_wise_report' => $order_wise_report,
            'orders' => $orders,
            'print' => 0
        ]);
    }

    public function getOrderWiseCuttingInventorySummaryData($orderId)
    {
        $report_data = TotalProductionReport::query();

        if ($orderId) {
            $report_data = $report_data->where('order_id', $orderId);
        }
        $report_data = $report_data->orderBy('buyer_id', 'desc')->paginate(PAGINATION);

        return $report_data;
    }

    public function orderWiseCuttingInventorySummaryDownload()
    {
        $type = request('type');
        $orderId = request('order_id');
        $page = request('page');

        if ($type == 'pdf') {
            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });

            $order_wise_report = $this->getOrderWiseCuttingInventorySummaryData($orderId);
            $print = 1;
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView('inputdroplets::reports.downloads.all-orders-inventory-report-download',
                compact('order_wise_report', 'print')
            )->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

            return $pdf->stream('all-orders-inventory-report.pdf');
        } else {
            return \Excel::download(new AllOrdersInventoryReportExport($orderId), 'all-orders-inventory-report.xlsx');
        }
    }

    public function getCuttingNoWiseInventoryChallanCount()
    {
        return view('inputdroplets::reports.cutting-no-wise-inventory-challan-count');
    }

    public function getCuttingNoWiseInventoryChallanCountPost(Request $request)
    {
        $purchase_order_id = $request->purchase_order_id;
        $color_id = $request->color_id;
        $cutting_no = $request->cutting_no;

        $cutting_no_wise_data = $this->getCuttingNoWiseInventoryChallanCountData($purchase_order_id, $color_id, $cutting_no);

        return $cutting_no_wise_data;
    }

    public function getCuttingNoWiseInventoryChallanCountData($purchase_order_id, $color_id, $cutting_no)
    {
        $cutting_no_wise_data = CuttingInventoryChallan::withoutGlobalScope('factoryId')
            ->join('cutting_inventories', 'cutting_inventory_challans.challan_no', 'cutting_inventories.challan_no')
            ->join('bundle_cards', 'cutting_inventories.bundle_card_id', 'bundle_cards.id')
            ->where([
                'bundle_cards.purchase_order_id' => $purchase_order_id,
                'bundle_cards.color_id' => $color_id,
                'bundle_cards.cutting_no' => $cutting_no,
                'cutting_inventory_challans.factory_id' => factoryId()
            ])
            ->groupBy('cutting_inventory_challans.challan_no')
            ->pluck('cutting_inventory_challans.challan_no')->all();

        return $cutting_no_wise_data;
    }

    public function getCuttingNoWiseInventoryChallanReportDownload($type, $purchase_order_id, $color_id, $cutting_no)
    {
        $data['cutting_no_wise_data'] = $this->getCuttingNoWiseInventoryChallanCountData($purchase_order_id, $color_id, $cutting_no);
        $order_query = PurchaseOrder::where('id', $purchase_order_id)->first();
        $data['buyer'] = $order_query->buyer->name ?? '';
        $data['booking_no'] = $order_query->order->booking_no ?? '';
        $data['style'] = $order_query->order->order_style_no ?? '';
        $data['order_no'] = $order_query->po_no ?? '';
        $data['color'] = Color::where('id', $color_id)->first()->name ?? '';
        $data['cutting_no'] = $cutting_no;
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('inputdroplets::reports.downloads.pdf.cutting-no-wise-inventory-challan-report-download', $data)
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('cutting-no-wise-inventory-challan-report.pdf');
        } else {
            return \Excel::download(new CuttingNoWiseInventoryChallanReportExport($data), 'cutting-no-wise-inventory-challan-report.xlsx');
        }
    }

    public function getInventoryChallanCount()
    {
        $buyers = Buyer::pluck('name', 'id')->all();

        return view('inputdroplets::reports.inventory-challan-count')->with('buyers', $buyers);
    }

    public function getInventoryChallanCountPost($purchase_order_id)
    {
        return CuttingInventoryChallan::withoutGlobalScopes()
            ->leftJoin('cutting_inventories', 'cutting_inventories.challan_no', 'cutting_inventory_challans.challan_no')
            ->leftJoin('bundle_cards', 'bundle_cards.id', 'cutting_inventories.bundle_card_id')
            ->where([
                'bundle_cards.purchase_order_id' => $purchase_order_id,
                'cutting_inventory_challans.factory_id' => factoryId(),
            ])
            ->select('cutting_inventory_challans.challan_no')
            ->groupBy('cutting_inventory_challans.challan_no')
            ->orderby('cutting_inventory_challans.challan_no', 'desc')
            ->pluck('cutting_inventory_challans.challan_no')->all();
    }

    public function getInventoryChallanCountReportDownload($type, $purchase_order_id)
    {
        $data['inventory_challan_count'] = $this->getInventoryChallanCountPost($purchase_order_id);
        $order_query = PurchaseOrder::where('id', $purchase_order_id)->first();
        $data['booking_no'] = $order_query->booking_no ?? '';
        $data['order_no'] = $order_query->po_no ?? '';
        $data['buyer'] = $order_query->buyer->name ?? '';
        $data['style'] = $order_query->order->order_style_no ?? '';
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('inputdroplets::reports.downloads.pdf.inventory-challan-count-report-download', $data)
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('inventory-challan-count-report.pdf');
        } else {
            return \Excel::download(new InventoryChallanCountReportExport($data), 'inventory-challan-count-report.xlsx');
        }
    }

}
