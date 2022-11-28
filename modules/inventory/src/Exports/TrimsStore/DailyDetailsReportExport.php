<?php

namespace SkylarkSoft\GoRMG\Inventory\Exports\TrimsStore;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DailyDetailsReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
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
        return 'Daily Details Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $reportData = $this->reportData;
        return view('inventory::trims-store.reports.daily-details-report.excel', compact('reportData'));
    }
}
