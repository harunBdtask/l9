<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class BuyerWiseWOReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
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
        return 'Buyer Wise WO Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('merchandising::reports.includes.budget_wise_workorder_report_table', $this->reportData);
    }
}
