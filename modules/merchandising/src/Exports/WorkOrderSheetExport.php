<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WorkOrderSheetExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    private $reportData;

    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    public function title(): string
    {
        return "Work Order Sheet";
    }

    public function view(): View
    {
        return view('merchandising::order.work-order-sheet-table', ['reportData' => $this->reportData]);
    }
}
