<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class POShipmentStatusReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $order_report, $buyer, $order_style_no;

    public function __construct($order_report, $buyer, $order_style_no)
    {
        $this->order_report = $order_report;
        $this->buyer = $buyer;
        $this->order_style_no = $order_style_no;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Shipment Status Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $order_report = $this->order_report;
        $buyer = $this->buyer;
        $order_style_no = $this->order_style_no;
        return view('finishingdroplets::reports.downloads.excels.po_shipment_status_download', compact('order_report', 'buyer', 'order_style_no'));
    }
}