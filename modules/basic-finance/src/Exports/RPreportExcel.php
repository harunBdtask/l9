<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RPreportExcel implements WithTitle, FromView, WithEvents
{
    use Exportable;

    private $reportData;

    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    public function title(): string
    {
        return 'Receipt and Payment Report';
    }

    public function view(): View
    {
        return view('basic-finance::print.receipt_payment_statement_excel', $this->reportData);
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $cellRange_1 = 'A6';
            $cellRange_2 = 'B6';
            $event->sheet->getDelegate()->getStyle($cellRange_1)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('A2:B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('A4:B4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle($cellRange_1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle($cellRange_2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }];
    }
}
