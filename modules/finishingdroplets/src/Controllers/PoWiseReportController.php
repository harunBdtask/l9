<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\FinishingProductionStatusReportExport;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\POShipmentStatusReportExport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\Finishing;
use PDF, Excel;

class PoWiseReportController extends Controller
{
    public function poShipmentStatus(Request $request)
    {
        $order_report = null;
        $buyer_id = $request->buyer_id ?? null;
        $order_id = $request->order_id ?? null;

        $buyers = Buyer::pluck('name', 'id');
        $order_style_list = $order_id ? Order::where('id', $order_id)->pluck('style_name', 'id') : [];

        if ($buyer_id && $order_id) {
            $order_report = PurchaseOrder::where(['buyer_id' => $buyer_id, 'order_id' => $order_id])->paginate();
            $currentPage = $order_report ? $order_report->currentPage() : 1;
            $pdf_download_link = "/po-shipment-status-report-download/" . $buyer_id . "/" . $order_id . "/" . $currentPage . "/pdf";
            $excel_download_link = "/po-shipment-status-report-download/" . $buyer_id . "/" . $order_id . "/" . $currentPage . "/excel";
        }

        return view('finishingdroplets::reports.po_shipment_status', [
            'buyers' => $buyers,
            'buyer_id' => $buyer_id,
            'order_id' => $order_id,
            'order_style_list' => $order_style_list,
            'order_report' => $order_report,
            'pdf_download_link' => $pdf_download_link ?? '#',
            'excel_download_link' => $excel_download_link ?? '#',
        ]);
    }

    public function poShipmentStatusReportDownload($buyer_id, $order_id, $current_page, $type)
    {
        Paginator::currentPageResolver(function () use ($current_page) {
            return $current_page;
        });
        $order_report = PurchaseOrder::where(['buyer_id' => $buyer_id, 'order_id' => $order_id])->paginate();
        $buyer = Buyer::where('id', $buyer_id)->first()->name ?? '';
        $order_style_no = Order::where('id', $order_id)->first()->order_style_no ?? '';
        if ($type == 'pdf') {
            //                return view('finishingdroplets::reports.downloads.pdf.finishing_production_status_download', compact('finishing_production_report'));
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView(
                    'finishingdroplets::reports.downloads.pdf.po_shipment_status_download',
                    compact('order_report', 'buyer', 'order_style_no')
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('po-shipment-status.pdf');
        } else {
            return \Excel::download(new POShipmentStatusReportExport($order_report, $buyer, $order_style_no), 'po-shipment-status.xlsx');

            /*\Excel::create('PO & Shipment Status Report', function ($excel) use ($order_report, $buyer, $order_style_no) {
                $excel->sheet('PO & Shipment Status', function ($sheet) use ($order_report, $buyer, $order_style_no) {
                    $sheet->loadView('finishingdroplets::reports.downloads.excels.po_shipment_status_download', compact('order_report', 'buyer', 'order_style_no'));
                });
            })->export('xls');*/
        }
        return redirect()->back();
    }

    public function finishingProductionStatusReport(Request $request)
    {
        $buyer_id = $request->buyer_id ?? null;
        $order_id = $request->order_id ?? null;
        
        $buyers = $buyer_id ? Buyer::where('id', $buyer_id)->pluck('name', 'id') : [];
        $orders = $order_id ? Order::where('id', $order_id)->pluck('style_name', 'id') : [];

        $finishing_production_report = $this->getFinishingProductionStatusReport($buyer_id, $order_id);
        $pdf_download_link = "/finishing-production-status-report-download/" . $buyer_id . "/" . $order_id . "/pdf";
        $excel_download_link = "/finishing-production-status-report-download/" . $buyer_id . "/" . $order_id . "/excel";

        return view('finishingdroplets::reports.finishing_production_status', [
            'buyers' => $buyers,
            'buyer_id' => $buyer_id,
            'order_id' => $order_id,
            'orders' => $orders,
            'finishing_production_report' => $finishing_production_report,
            'pdf_download_link' => $pdf_download_link,
            'excel_download_link' => $excel_download_link,
        ]);
    }

    public function getFinishingProductionStatusReport($buyer_id = null, $order_id = null)
    {
        if (!$buyer_id && !$order_id) {
            return null;
        }
        return TotalProductionReport::query()
            ->with('buyer', 'order', 'purchaseOrder.purchaseOrderDetails', 'colors')
            ->when(($buyer_id && !$order_id), function($query) use($buyer_id) {
                $query->where('buyer_id', $buyer_id);
            })
            ->when($order_id, function($query) use($order_id) {
                $query->where('order_id', $order_id);
            })
            ->get();
    }

    public function finishingProductionStatusReportDownload($buyer_id, $order_id, $type)
    {
        $finishing_production_report = $this->getFinishingProductionStatusReport($buyer_id, $order_id);
        $buyer = Buyer::where('id', $buyer_id)->first()->name ?? '';
        $order_style_no = Order::where('id', $order_id)->first()->order_style_no ?? '';

        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView(
                    'finishingdroplets::reports.downloads.pdf.finishing_production_status_download',
                    compact('finishing_production_report', 'buyer', 'order_style_no')
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('finishing-production-status.pdf');
        } else {
            return \Excel::download(new FinishingProductionStatusReportExport($finishing_production_report, $buyer, $order_style_no), 'finishing-production-status.xlsx');
        }
    }
}
