<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BudgetExcel implements WithTitle, FromView, WithEvents
{
    use Exportable;

    private $budgets;

    public function __construct($budgets)
    {
        $this->budgets = $budgets;
    }

    public function title(): string
    {
        return 'Budget List View';
    }

    public function view(): View
    {
        $budgets = $this->budgets;
        return view('merchandising::budget.budget-list-excel', compact('budgets'));
    }

    public function registerEvents() : array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $cellRange = 'A1:L1';
            $getHighestRow = $event->sheet->getDelegate()->getHighestRow();
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:L1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }];
    }


}
