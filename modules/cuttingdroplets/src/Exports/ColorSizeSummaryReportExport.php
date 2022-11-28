<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ColorSizeSummaryReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $reportData;

    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Color Size Summary Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $reportData = $this->reportData;

        return view('cuttingdroplets::reports.downloads.excels.color_size_summary_report', $reportData);
    }
}
