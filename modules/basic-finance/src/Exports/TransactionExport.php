<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use SkylarkSoft\GoRMG\BasicFinance\PackageConst;

class TransactionExport implements FromView, WithTitle, WithEvents
{
    use Exportable;

    private $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function title(): string
    {
        return 'Transaction List';
    }

    public function view(): View
    {
        $transactions = $this->transactions;
        return view(PackageConst::PACKAGE_NAME . '::print.transactions_excel', compact('transactions'));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange_1 = 'A6:I6';
                $cellRange_2 = 'B6';
                $cellRange_3 = 'C6';
                $event->sheet->getDelegate()->getStyle($cellRange_1)->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1:B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('A2:B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('A4:B4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle($cellRange_1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle($cellRange_2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle($cellRange_3)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            }
        ];
    }
}
