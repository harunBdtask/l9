<?php

namespace SkylarkSoft\GoRMG\Subcontract\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;

class OrderWiseStockReportExport implements WithTitle, ShouldAutoSize, FromView
{
    private $reportData;

    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    public function view(): View
    {
        return view(PackageConst::VIEW_PATH . 'report.orderWiseStockReport.excel', [
            'reportData' => $this->reportData,
        ]);
    }

    public function title(): string
    {
        return "Order Wise Stock Report";
    }
}
