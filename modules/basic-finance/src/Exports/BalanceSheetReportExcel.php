<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BalanceSheetReportExcel implements WithTitle, FromView, WithEvents
{
    use Exportable;

    private $reportData;

    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    public function title(): string
    {
        return 'Balance Sheet Report';
    }

    public function view(): View
    {
        return view('basic-finance::print.balance_sheet_v2_excel', $this->reportData);
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $highestRowNumber =  $event->sheet->getDelegate()->getHighestRow();
            $cellRange_1 = 'A4';
            $cellRange_2 = 'C4';
            $cellRange_3 = 'C10:C'.$highestRowNumber;
            $event->sheet->getDelegate()->getStyle($cellRange_1)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A2:C2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A4:C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle($cellRange_3)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle($cellRange_1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle($cellRange_2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }];
    }
}
