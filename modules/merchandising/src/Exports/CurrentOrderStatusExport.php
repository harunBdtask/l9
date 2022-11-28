<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class CurrentOrderStatusExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $orders, $header;

    public function __construct($data, $header, $reportID=null)
    {
        $this->orders = $data;
        $this->header = $header;
        $this->reportID = $reportID;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Current Order Status Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $orders = $this->orders;
        $header = $this->header;
        return view('merchandising::reports.order-current-status.table'.$this->reportID, compact('orders', 'header'));
    }
}
