<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class OrderColorWiseSummaryExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    private $reportData;

    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    public function title(): string
    {
        return "Order Color Wise Summary Report";
    }

    public function view(): View
    {
        return view('merchandising::order.color_wise_summary.excel', $this->reportData);
    }
}
