<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ColorWiseCuttingReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $result_data, $buyer, $style, $order_no;

    public function __construct($result_data, $buyer, $style, $order_no)
    {
        $this->result_data = $result_data;
        $this->style = $style;
        $this->buyer = $buyer;
        $this->order_no = $order_no;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Color Wise Cutting Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $result_data = $this->result_data;
        $style = $this->style;
        $buyer = $this->buyer;
        $order_no = $this->order_no;
        return view('cuttingdroplets::reports.downloads.excels.order-wise-cutting-report', compact('result_data', 'buyer', 'style', 'order_no'));
    }
}