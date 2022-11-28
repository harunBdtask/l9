<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class GroupLedgerReportExcelNew implements WithTitle, FromView, WithEvents
{
    use Exportable;

    private $reportData;

    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    public function title(): string
    {
        return 'Group Ledger';
    }

    public function view(): View
    {
        return view('basic-finance::print.group_ledger_excel_new', $this->reportData);
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $highestRowNumber =  $event->sheet->getDelegate()->getHighestRow();
            $cellRange_3 = 'B8:B'.$highestRowNumber;
            $cellRange_0 = 'D8:D'.$highestRowNumber;
            $cellRange_1 = 'F8:F'.$highestRowNumber;
            $cellRange_2 = 'E8:E'.$highestRowNumber;
            $event->sheet->getDelegate()->getStyle('A1:E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A2:E2')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A2:E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A7:E7')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A7:E7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle($cellRange_3)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle($cellRange_0)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle($cellRange_1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle($cellRange_2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }];
    }
}
