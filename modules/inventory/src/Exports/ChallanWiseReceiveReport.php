<?php

namespace SkylarkSoft\GoRMG\Inventory\Exports;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ChallanWiseReceiveReport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
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
        return 'Challan Wise Receive Statement';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('inventory::yarns.reports.challan-wise-receive-statement.excel', [
            'reportData' => $this->reportData
        ]);
    }
}
