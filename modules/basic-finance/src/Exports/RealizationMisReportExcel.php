<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RealizationMisReportExcel implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $lists;
    private $start_date;
    private $end_date;

    public function __construct($lists,$start_date,$end_date)
    {
        $this->lists = $lists;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function title(): string
    {
        return 'Realization MIS Report';
    }

    public function view(): View
    {
        $lists = $this->lists;
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        return view('basic-finance::accounting-realization.reports.mis_report_excel', [
            'lists' => $lists,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }
}
