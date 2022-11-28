<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class BuyerStyleWiseCuttingReportExport implements WithTitle, ShouldAutoSize, FromView
{
    use Exportable;

    private $reportData;

    public function __construct($data)
    {
        $this->reportData = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Buyer Style wise Cutting Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        return view('cuttingdroplets::reports.downloads.excels.buyer_style_wise_cutting_report_excel', $this->reportData);
    }
}
