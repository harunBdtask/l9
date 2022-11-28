<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DateWiseCuttingReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $cutting_report;

    public function __construct($cutting_report)
    {
        $this->cutting_report = $cutting_report;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Date Wise Cutting Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->cutting_report;
        
        return view('cuttingdroplets::reports.tables.date_wise_cutting_summary', $data);
    }
}