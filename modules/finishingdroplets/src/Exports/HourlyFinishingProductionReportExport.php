<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class HourlyFinishingProductionReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $reportData, $total;

    public function __construct($data, $total)
    {
        $this->reportData = $data;
        $this->total = $total;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Hourly Finishing Production Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {

        return view('finishingdroplets::reports.tables.hourly_finishing_production_table',
            ['reportData' => $this->reportData, 'total' => $this->total]);
    }
}
