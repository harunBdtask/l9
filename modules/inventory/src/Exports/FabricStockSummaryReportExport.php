<?php

namespace SkylarkSoft\GoRMG\Inventory\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class FabricStockSummaryReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
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
        return 'Fabric Stock Summary Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $reportData = $this->reportData;
        return view('inventory::reports.fabric_stock_summery_report.table', $reportData);
    }
}
