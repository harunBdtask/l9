<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DailyChallanSizeWiseExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    private $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function view(): View
    {
        return view('inputdroplets::reports.downloads.excels.daily_size_wise_input_report_excel', $this->report);
    }

    public function title(): string
    {
        return 'Daily Size Wise Export';
    }
}
