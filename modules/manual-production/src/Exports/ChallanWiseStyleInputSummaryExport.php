<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ChallanWiseStyleInputSummaryExport implements FromView, ShouldAutoSize
{
    use Exportable;

    protected $reports, $orders, $order_id, $buyer_id, $buyers;

    public function __construct($reports, $orders, $order_id, $buyer_id, $buyers)
    {
        $this->reports = $reports;
        $this->orders = $orders;
        $this->order_id = $order_id;
        $this->buyers = $buyers;
        $this->buyer_id = $buyer_id;
    }

    public function view(): View
    {
        $order_id = $this->order_id;
        $orders = $this->orders;
        $reports = $this->reports;
        $buyers = $this->buyers;
        $buyer_id = $this->buyer_id;
        return view('manual-production::reports.sewing.includes.challan_wise_style_input_summary_inlcude',
            compact('order_id', 'orders', 'reports', 'buyers', 'buyer_id'));
    }
}
