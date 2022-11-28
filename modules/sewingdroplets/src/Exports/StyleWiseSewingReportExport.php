<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class StyleWiseSewingReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $result_data, $style, $buyer;

    public function __construct($data, $style, $buyer)
    {
        $this->result_data = $data;
        $this->style = $style;
        $this->buyer = $buyer;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Reference Wise Sewing Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->result_data;
        $style = $this->style;
        $buyer = $this->buyer;
        return view('sewingdroplets::reports.downloads.excels.style-wise-sewing-output-report-download', compact('data', 'style', 'buyer'));
    }
}