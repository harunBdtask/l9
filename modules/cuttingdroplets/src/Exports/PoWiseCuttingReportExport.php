<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class PoWiseCuttingReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $result_data, $order_no, $buyer, $style;

    public function __construct($result_data, $order_no, $buyer, $style)
    {
        $this->result_data = $result_data;
        $this->order_no = $order_no;
        $this->buyer = $buyer;
        $this->style = $style;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'PO Wise Cutting Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $result_data = $this->result_data;
        $order_no = $this->order_no;
        $buyer = $this->buyer;
        $style = $this->style;
        return view('cuttingdroplets::reports.downloads.excels.order-wise-cutting-report', compact('result_data', 'order_no', 'buyer', 'style'));
    }
}