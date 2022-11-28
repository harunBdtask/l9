<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class YearlySummaryReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    protected $reportData;

    public function __construct($data)
    {
        $this->reportData = $data;
    }

    public function view(): View
    {
        return view("cuttingdroplets::reports.downloads.excels.yearly_summary_report_excel",
            $this->reportData);
    }

    public function title(): string
    {
        return "Yearly Summary Report";
    }
}
