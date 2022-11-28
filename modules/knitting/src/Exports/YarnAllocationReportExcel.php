<?php

namespace SkylarkSoft\GoRMG\Knitting\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class YarnAllocationReportExcel implements WithTitle, FromView, WithEvents
{
    use Exportable;

    private $reportDate;

    public function __construct($reportDate)
    {
        $this->reportDate = $reportDate;
    }

    public function title(): string
    {
        return 'Yarn Allocation Report';
    }

    public function view(): View
    {
        return view('knitting::reports.yarn-allocation-report.excel', $this->reportDate);
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $cellRange = 'A3:U3';
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:U1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('A5:U5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('A4:U4')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }];
    }
}
