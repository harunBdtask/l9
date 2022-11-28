<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class CuttingProductionReportV2Export implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    protected $reportData;

    public function __construct($data)
    {
        $this->reportData = $data;
    }

    public function view(): View
    {
        return view("cuttingdroplets::reports.downloads.excels.cutting_production_report_v2_excel",
            ['reportData' => $this->reportData]);
    }

    public function title(): string
    {
        return "Cutting Production Report V2";
    }
}
