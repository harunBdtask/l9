<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DateWisePolyCartoonReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $reports;
    private $from_date;
    private $to_date;
    private $type;

    public function __construct($reports, $type, $from_date, $to_date)
    {
        $this->reports = $reports;
        $this->type = $type;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Date Wise Poly Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $reports = $this->reports;
        $type = $this->type;
        $from_date = $this->from_date;
        $to_date = $this->to_date;

        return view('finishingdroplets::reports.tables.date_range_wise_poly_cartoon_report', compact('reports','type', 'from_date', 'to_date'));
    }
}