<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ProvisionalLedgerReportExcel implements WithTitle, FromView, WithEvents
{
    use Exportable;

    private $reportData;

    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    public function title(): string
    {
        return 'Provisional Ledger Report';
    }

    public function view(): View
    {
        return view('basic-finance::print.provisional_ledger_excel', $this->reportData);
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $highestRowNumber =  $event->sheet->getDelegate()->getHighestRow();
            $cellRange_3 = 'B11:B' . $highestRowNumber;
            $cellRange_0 = 'I11:I' . $highestRowNumber;
            $cellRange_1 = 'J11:J' . $highestRowNumber;
            $cellRange_2 = 'R11:R' . $highestRowNumber;
            $event->sheet->getDelegate()->getStyle('A1:R1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A2:R2')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A4:R4')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A9:R9')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A10:R10')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A2:R2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A4:R4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A9:R9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A10:R10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A7:R7')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A7:R7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle($cellRange_3)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle($cellRange_0)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle($cellRange_1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle($cellRange_2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }];
    }
}
