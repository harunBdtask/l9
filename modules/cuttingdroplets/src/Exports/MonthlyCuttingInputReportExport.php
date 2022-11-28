<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class MonthlyCuttingInputReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    protected $reportData;

    public function __construct($data)
    {
        $this->reportData = $data;
    }

    public function view(): View
    {
        return view("cuttingdroplets::reports.downloads.excels.monthly_cutting_input_report",
            $this->reportData);
    }

    public function title(): string
    {
        return "Monthly Cutting Input Report";
    }
}
