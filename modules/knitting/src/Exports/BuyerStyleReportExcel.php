<?php

namespace SkylarkSoft\GoRMG\Knitting\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BuyerStyleReportExcel implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $plannings;

    public function __construct($plannings)
    {
        $this->plannings = $plannings;
    }


    public function view(): View
    {
        $plannings = $this->plannings;
        return view('knitting::reports.buyer-style-report.buyer-style-report-excel', compact('plannings'));
    }

    public function title(): string
    {
        return 'Buyer Style Report';
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $cellRange = 'A1:H1';
            $getHighestRow = $event->sheet->getDelegate()->getHighestRow();
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:H' . $getHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }];
    }
}
