<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class FinishingProductionReportV2Export implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    protected $reportData;

    public function __construct($data)
    {
        $this->reportData = $data;
    }

    public function view(): View
    {
        return view("finishingdroplets::reports.downloads.excels.finishing_production_report_v2_excel",
            ['reportData' => $this->reportData]);
    }

    public function title(): string
    {
        return "Finishing Production Report";
    }
}
