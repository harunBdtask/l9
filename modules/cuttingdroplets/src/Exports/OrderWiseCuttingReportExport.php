<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class OrderWiseCuttingReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $result_data, $style, $buyer;

    public function __construct($result_data, $style, $buyer)
    {
        $this->result_data = $result_data;
        $this->style = $style;
        $this->buyer = $buyer;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Order Wise Cutting Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $result_data = $this->result_data;
        $style = $this->style;
        $buyer = $this->buyer;
        return view('cuttingdroplets::reports.downloads.excels.style-wise-report-download', compact('result_data', 'style', 'buyer'));
    }
}