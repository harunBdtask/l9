<?php

namespace SkylarkSoft\GoRMG\Planing\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class CapacityMarketingComparisonReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $reports;

    public function __construct($data)
    {
        $this->reports = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Capacity VS Marketing Realization Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('planing::reports.capacity-marketing-comparison-table', $this->reports);
    }
}
